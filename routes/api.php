<?php

use App\Models\HusmusenDBInfo;
use App\Models\HusmusenError;
use App\Models\HusmusenFile;
use App\Models\HusmusenItem;
use App\Models\HusmusenKeyword;
use App\Models\HusmusenLog;
use App\Models\HusmusenUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;

use function Illuminate\Filesystem\join_paths;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "api" middleware group. Make something great!
 * |
 */

/*
 * NOTE: Every route in this file is automatically mounted under `/api`!
 */

// KEEP THIS FOR REFERENCE.
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

function response_handler($data, $request, $status = 200)
{
    $output_format = $request->header('Husmusen-Output-Format', 'application/json');

    if ('application/yaml' == $output_format) {
        return FacadeResponse::make(Yaml::dump(json_decode(json_encode($data), true), 2, 4), $status, ['Content-Type' => 'application/yaml']);
    } else {
        return FacadeResponse::make(json_encode($data), $status, ['Content-Type' => 'application/json']);
    }
}

/*
 * PUBLIC ROUTES
 */

Route::get('/db_info', function () {
    $db_info = HusmusenDBInfo::get_db_info();

    return response_handler($db_info, request());
});

Route::get('/db_info/version', function () {
    $db_info = HusmusenDBInfo::get_db_info();

    return response($db_info->protocolVersion)->header('Content-Type', 'text/plain');
});

Route::get('/db_info/versions', function () {
    $db_info = HusmusenDBInfo::get_db_info();

    return response(join(',', $db_info->protocolVersions))->header('Content-Type', 'text/plain');
});

Route::get('/1.0.0/item/search', function (Request $request) {
    // Get all the parameters from the request.
    $types = $request->query('types', '');
    $freetext = $request->query('freetext', '');
    $keywords = $request->query('keywords', '');
    $keyword_mode = $request->query('keyword-mode', 'OR');
    $order_by = $request->query('sort', 'name');
    $reverse = $request->query('reverse', 'false');

    return HusmusenItem::search_v1_0_0($types, $freetext, $keywords, $keyword_mode, $order_by, $reverse);
});

Route::get('/1.0.0/item/info/{id}', function (string $id) {
    $item = HusmusenItem::find($id);
    if (!$item) {
        return HusmusenError::create(404, 'ERR_ITEM_NOT_FOUND', 'It appears this item does not exist.');
    }

    return response_handler($item, request());
});

Route::get('/1.0.0/file/get/{id}', function (string $id) {
    $file = HusmusenFile::find($id);
    $file_path = join_paths(base_path('data/files'), $id);

    if (!$file) {
        return HusmusenError::create(404, 'ERR_FILE_NOT_FOUND', 'It appears this file does not exist.');
    }

    $response = FacadeResponse::make(File::get($file_path));
    $response->header('Content-Type', $file->type);

    return $response;
});

Route::get('/1.0.0/file/info/{id}', function (string $id) {
    $file = HusmusenFile::find($id);
    if (!$file) {
        return HusmusenError::create(404, 'ERR_FILE_NOT_FOUND', 'It appears this file does not exist.');
    }

    return response_handler($file, request());
});

Route::get('/1.0.0/keyword', function (Request $request) {
    return response_handler(HusmusenKeyword::get_all(), $request);
});

Route::get('/1.0.0/keyword/{types}', function (Request $request, string $types) {
    $types_array = explode(',', $types);

    return response_handler(HusmusenKeyword::get_all_by_types($types_array), $request);
});

/*
 * LOG IN / AUTH SYSTEM
 */
Route::post('/auth/login', function (Request $request) {
    $username = $request->input('username');
    $password = $request->input('password');

    if (!$username) {
        return HusmusenError::create(400, 'ERR_MISSING_PARAMETER', "You must specify 'username'.");
    }

    if (!$password) {
        return HusmusenError::create(400, 'ERR_MISSING_PARAMETER', "You must specify 'password'.");
    }

    $user = HusmusenUser::find($username);

    if (!$user) {
        return HusmusenError::create(500, 'ERR_USER_NOT_FOUND', 'There was an error looking up the user!');
    }

    $pass_is_valid = Hash::check($password, $user->password);

    if (!$pass_is_valid) {
        return HusmusenError::create(400, 'ERR_INVALID_PASSWORD', 'Incorrect password.');
    }

    // 4 hours in the future.
    $valid_until = time() + 14400;
    $token = HusmusenUser::get_token($user, $valid_until);

    HusmusenLog::write('Auth', sprintf("%s '%s' logged in!", ($user->isAdmin) ? 'Admin' : 'User', $user->username));

    return response()->json([
        'token' => $token,
        'validUntil' => date('c', $valid_until),  // Format date as per ISO 8601.
    ]);
});

Route::post('/auth/who', function (Request $request) {
    $token = $request->header('Husmusen-Access-Token');

    return response_handler(HusmusenUser::decode_token($token), request());
})->middleware('auth:user');

Route::post('/auth/new', function (Request $request) {
    $username = $request->input('username');
    $password = $request->input('password');
    $is_admin = $request->input('isAdmin');

    if (!$username) {
        return HusmusenError::create(400, 'ERR_MISSING_PARAMETER', "You must specify 'username'.");
    }

    if (!$password) {
        return HusmusenError::create(400, 'ERR_MISSING_PARAMETER', "You must specify 'password'.");
    }

    $user = HusmusenUser::find($username);

    if ($user) {
        return HusmusenError::create(400, 'ERR_ALREADY_EXISTS', 'That user already exists!');
    }

    $user = HusmusenUser::create([
        'username' => $username,
        'password' => Hash::make($password),
        'isAdmin' => 'on' == $is_admin,
    ]);

    HusmusenLog::write(
        'Database',
        sprintf(
            "'%s' created an account with the username '%s' (%s)!",
            $request->get('auth_username'),
            $username,
            'on' == $is_admin ? 'Admin' : 'User',
        )
    );

    return response_handler(response()->json($user), request());
})->middleware('auth:admin');

Route::post('/auth/delete', function (Request $request) {
    $username = $request->input('username');

    if (!$username) {
        return HusmusenError::create(400, 'ERR_MISSING_PARAMETER', "You must specify 'username'.");
    }

    if ($username == $request->get('auth_username')) {
        return HusmusenError::create(402, 'ERR_FORBIDDEN_ACTION', 'You cannot delete yourself!');
    }

    $user = HusmusenUser::find($username);

    if (!$user) {
        return HusmusenError::create(400, 'ERR_USER_NOT_FOUND', 'There is no user with that username!');
    }

    if (!$user->delete()) {
        return HusmusenError::create(500, 'ERR_DATABASE_ERROR', 'There was an error deleting that user!');
    }

    HusmusenLog::write(
        'Database',
        sprintf(
            "Admin '%s' deleted the user '%s'!",
            $request->get('auth_username'),
            $username,
        )
    );

    return response_handler([$username], request());
})->middleware('auth:admin');

Route::post('/auth/change_password', function (Request $request) {
    $current_password = $request->input('currentPassword');
    $new_password = $request->input('newPassword');

    if (!$current_password) {
        return HusmusenError::create(400, 'ERR_MISSING_PARAMETER', "You must specify 'currentPassword'.");
    }

    if (!$new_password) {
        return HusmusenError::create(400, 'ERR_MISSING_PARAMETER', "You must specify 'newPassword'.");
    }

    // This will be safe, it will always return a valid user.
    // The middleware will make sure that there's never a non-logged-in user here.
    // By extent, this always returns an existing user.
    $token = $request->header('Husmusen-Access-Token');
    $decoded_token = HusmusenUser::decode_token($token);
    $who = HusmusenUser::find($decoded_token->sub);

    $password_matches = Hash::check($current_password, $who->password);
    if (!$password_matches) {
        return HusmusenError::create(401, 'ERR_INVALID_PASSWORD', "Your provided 'currentPassword' is not correct!");
    }

    DB::table('husmusen_users')
        ->where('username', $who->sub)
        ->update(['password' => Hash::make($new_password)]);

    return response_handler(['username' => $who->username, 'password' => $new_password], request());
})->middleware('auth:user');

if (env('APP_DEBUG', false)) {
    Route::post('/auth/debug_admin_creation', function (Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        if (!$username) {
            return HusmusenError::create(400, 'ERR_MISSING_PARAMETER', "You must specify 'username'.");
        }

        if (!$password) {
            return HusmusenError::create(400, 'ERR_MISSING_PARAMETER', "You must specify 'password'.");
        }

        $user = HusmusenUser::find($username);

        if ($user) {
            return HusmusenError::create(400, 'ERR_ALREADY_EXISTS', 'That user already exists!');
        }

        return response_handler(
            HusmusenUser::create([
                'username' => $username,
                'password' => Hash::make($password),
                'isAdmin' => true,
            ]),
            $request
        );
    });
}

/*
 * PROTECTED ROUTES
 */
Route::post('/1.0.0/item/new', function (Request $request) {
    $item_to_create = HusmusenItem::from_array_data($request->all());
    $save_succeeded = $item_to_create->save();

    if (!$save_succeeded) {
        return HusmusenError::create(500, 'ERR_DATABASE_ERROR', 'Something went wrong while saving the item!');
    }

    HusmusenLog::write(
        'Database',
        sprintf(
            "%s '%s' created item with ID '%s'!",
            $request->get('auth_is_admin') ? 'Admin' : 'User',
            $request->get('auth_username'),
            $item_to_create->itemID
        )
    );

    return response_handler($item_to_create, request());
})->middleware('auth:user')->middleware('yaml_parser');

Route::post('/1.0.0/item/edit', function (Request $request) {
    $item_id = $request->input('itemID');
    $item_to_update = HusmusenItem::find($item_id);

    if (!$item_to_update) {
        return HusmusenError::create(404, 'ERR_OBJECT_NOT_FOUND', "The item you're trying to edit does not exist!");
    }

    $new_item_data = $request->input('newItemData');
    $save_succeeded = HusmusenItem::update_from_array_data($item_to_update, $new_item_data);

    if (!$save_succeeded) {
        return HusmusenError::create(500, 'ERR_DATABASE_ERROR', 'Something went wrong while saving the item!');
    }

    HusmusenLog::write(
        'Database',
        sprintf(
            "%s '%s' updated item with ID '%s'!",
            $request->get('auth_is_admin') ? 'Admin' : 'User',
            $request->get('auth_username'),
            $item_to_update->itemID
        )
    );

    // TODO: I don't know if this data will be changed or not.
    // Depends on if it is passed by reference or value... (Check!)
    return response_handler($item_to_update, request());
})->middleware('auth:user')->middleware('yaml_parser');

Route::post('/1.0.0/item/mark', function (Request $request) {
    $item_id = $request->input('itemID');
    $reason = $request->input('reason');

    if (!$item_id) {
        return HusmusenError::create(400, 'ERR_MISSING_PARAMETER', "You must specify 'itemID'.");
    }

    if (!$reason) {
        return HusmusenError::create(400, 'ERR_MISSING_PARAMETER', "You must specify 'reason'.");
    }

    $item = HusmusenItem::find($item_id);
    if (!$item) {
        return HusmusenError::create(404, 'ERR_ITEM_NOT_FOUND', 'It appears this item does not exist.');
    }

    $item->isExpired = true;
    $item->expireReason = $request->input('reason');

    $save_succeeded = $item->save();
    if (!$save_succeeded) {
        return HusmusenError::create(500, 'ERR_DATABASE_ERROR', 'Something went wrong while saving the item!');
    }

    HusmusenLog::write(
        'Database',
        sprintf(
            "%s '%s' marked item with ID '%s' as expired with the reason '%s'!",
            $request->get('auth_is_admin') ? 'Admin' : 'User',
            $request->get('auth_username'),
            $item_id,
            $reason,
        )
    );

    return response_handler($item, request());
})->middleware('auth:user');

Route::post('/1.0.0/file/new', function (Request $request) {
    $file_to_create = HusmusenFile::from_array_data($request->all());
    $save_succeeded = $file_to_create->save();

    if (!$save_succeeded) {
        return HusmusenError::create(500, 'ERR_DATABASE_ERROR', 'Something went wrong while saving the file metadata!');
    }

    $base64_image = $request->input('fileDataURL');
    @list($file_to_create->type, $file_data) = explode(';', $base64_image);
    @list(, $file_data) = explode(',', $file_data);
    Storage::disk('files')->put($file_to_create->fileID, base64_decode($file_data));

    HusmusenLog::write(
        'Database',
        sprintf(
            "%s '%s' created file with ID '%s'!",
            $request->get('auth_is_admin') ? 'Admin' : 'User',
            $request->get('auth_username'),
            $file_to_create->fileID
        )
    );

    return response_handler($file_to_create, request());
})->middleware('auth:user')->middleware('yaml_parser');

Route::post('/1.0.0/file/edit/', function (Request $request) {
    $fileID = $request->input('fileID');
    $file_to_update = HusmusenFile::find($fileID);

    if (!$file_to_update) {
        return HusmusenError::create(404, 'ERR_OBJECT_NOT_FOUND', "The item you're trying to edit does not exist!");
    }

    $newFileData = $request->input('newFileData');
    $save_succeeded = HusmusenFile::update_from_array_data($file_to_update, $newFileData);

    if (!$save_succeeded) {
        return HusmusenError::create(500, 'ERR_DATABASE_ERROR', 'Something went wrong while saving the file!');
    }

    HusmusenLog::write(
        'Database',
        sprintf(
            "%s '%s' updated file with ID '%s'!",
            $request->get('auth_is_admin') ? 'Admin' : 'User',
            $request->get('auth_username'),
            $file_to_update->fileID
        )
    );

    // TODO: I don't know if this data will be changed or not.
    // Depends on if it is passed by reference or value... (Check!)
    return response_handler($file_to_update, $request);
})->middleware('auth:user')->middleware('yaml_parser');

Route::post('/1.0.0/file/delete', function (Request $request) {
    $id = $request->input('fileID');
    if (!$id) {
        return HusmusenError::create(400, 'ERR_MISSING_PARAMETER', "You must specify 'fileID'.");
    }

    $file = HusmusenFile::find($id);
    if (!$file) {
        return HusmusenError::create(400, 'ERR_FILE_NOT_FOUND', "That item doesn't exist!");
    }

    $file_path = join_paths(base_path('data/files'), $id);
    File::delete($file_path);

    HusmusenFile::destroy($id);

    HusmusenLog::write('Database', sprintf("%s '%s' deleted item with ID '%d'!", ($request->get('auth_is_admin')) ? 'Admin' : 'User', $request->get('auth_username'), $id));

    return response_handler($file, request());
})->middleware('auth:user')->middleware('yaml_parser');

/*
 * PROTECTED ROUTES (ADMIN ONLY)
 */
Route::post('/db_info', function (Request $request) {
    return response_handler(HusmusenDBInfo::update_from_array_data($request->all()), request());
})->middleware('auth:admin')->middleware('yaml_parser');

Route::post('/1.0.0/item/delete', function (Request $request) {
    $id = $request->input('itemID');
    if (!$id) {
        return HusmusenError::create(400, 'ERR_MISSING_PARAMETER', "You must specify 'itemID'.");
    }

    $item = HusmusenItem::find($id);
    if (!$item) {
        return HusmusenError::create(400, 'ERR_OBJECT_NOT_FOUND', "That item doesn't exist!");
    }

    $files = HusmusenFile::all()->where('relatedItem', '==', $id);

    foreach ($files as $file) {
        $file_path = join_paths(base_path('data/files'), $file->fileID);
        File::delete($file_path);
        HusmusenFile::destroy($file->fileID);
    }

    HusmusenItem::destroy($id);
    HusmusenLog::write('Database', sprintf("%s '%s' deleted item with ID '%d'!", ($request->get('auth_is_admin')) ? 'Admin' : 'User', $request->get('auth_username'), $id));

    return response_handler($item, request());
})->middleware('auth:admin');

Route::post('/1.0.0/keyword', function (Request $request) {
    try {
        $keywords = array_map(
            fn ($keyword): array => (array) HusmusenKeyword::from_array_data($keyword),
            $request->input('keywords')
        );
        HusmusenKeyword::update_keywords($keywords);
    } catch (Throwable $th) {
        return HusmusenError::create(400, 'ERR_UNKNOWN_ERROR', 'There was an error saving the keywords...');
    }

    return response_handler(HusmusenKeyword::get_all(), request());
})->middleware('auth:admin')->middleware('yaml_parser');

Route::get('/1.0.0/log/get', function (Request $request) {
    $reverse = $request->query('reverse', 'on');
    if (in_array($reverse, ['on', '1', 'true'])) {
        return response()->json(HusmusenLog::orderByDesc('timestamp')->get());
    }

    return response_handler(HusmusenLog::orderBy('timestamp')->get(), request());
})->middleware('auth:admin');
