<?php

use App\Models\HusmusenDBInfo;
use App\Models\HusmusenError;
use App\Models\HusmusenFile;
use App\Models\HusmusenItem;
use App\Models\HusmusenLog;
use App\Models\HusmusenUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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

/*
 * PUBLIC ROUTES
 */

Route::get('/db_info', function () {
    $db_info = HusmusenDBInfo::get_db_info();
    return response()->json($db_info);
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
        return HusmusenError::SendError(404, 'ERR_ITEM_NOT_FOUND', 'It appears this item does not exist.');
    }
    return $item;
});

Route::get('/1.0.0/file/get/{id}', function (string $id) {
    // TODO: Return file data!
    return 'NOT IMPLEMENTED';
});

Route::get('/1.0.0/file/info/{id}', function (string $id) {
    $file = HusmusenFile::find($id);
    if (!$file) {
        return HusmusenError::SendError(404, 'ERR_FILE_NOT_FOUND', 'It appears this file does not exist.');
    }
});

Route::get('/1.0.0/keyword', function () {
    return 'NOT IMPLEMENTED';
});

/*
 * LOG IN / AUTH SYSTEM
 */
Route::post('/auth/login', function (Request $request) {
    $username = $request->input('username');
    $password = $request->input('password');

    if (!$username)
        return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'username'.");

    if (!$password)
        return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'password'.");

    $user = HusmusenUser::find($username);

    if (!$user)
        return HusmusenError::SendError(500, 'ERR_USER_NOT_FOUND', 'There was an error looking up the user!');

    $pass_is_valid = Hash::check($password, $user->password);

    if (!$pass_is_valid)
        return HusmusenError::SendError(400, 'ERR_INVALID_PASSWORD', 'Incorrect password.');

    // 4 hours in the future.
    $valid_until = time() + 14400;
    $token = HusmusenUser::get_token($user, $valid_until);

    HusmusenLog::write('Auth', sprintf("%s '%s' logged in!", ($user->isAdmin) ? 'Admin' : 'User', $user->username));

    return response()->json([
        'token' => $token,
        'validUntil' => date('c', $valid_until)  // Format date as per ISO 8601.
    ]);
});

Route::post('/auth/who', function (Request $request) {
    $token = $request->header('Husmusen-Access-Token');
    return HusmusenUser::decode_token($token);
})->middleware('auth:user');

Route::post('/auth/new', function (Request $request) {
    $username = $request->input('username');
    $password = $request->input('password');
    $isAdmin = $request->input('isAdmin');

    if (!$username)
        return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'username'.");

    if (!$password)
        return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'password'.");

    $user = HusmusenUser::find($username);

    if ($user)
        return HusmusenError::SendError(400, 'ERR_ALREADY_EXISTS', 'That user already exists!');

    $user = HusmusenUser::create([
        'username' => $username,
        'password' => Hash::make($password),
        'isAdmin' => $isAdmin == 'on',
    ]);

    HusmusenLog::write(
        'Database',
        sprintf(
            "'%s' created an account with the username '%s' (%s)!",
            request()->query('auth_username'),
            $username,
            $isAdmin == 'on' ? 'Admin' : 'User',
        )
    );

    return response()->json($user);
})->middleware('auth:admin');

Route::post('/auth/delete', function (Request $request) {
    $username = $request->input('username');

    if (!$username)
        return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'username'.");

    if ($username == request()->query('auth_username'))
        return HusmusenError::SendError(402, 'ERR_FORBIDDEN_ACTION', 'You cannot delete yourself!');

    $user = HusmusenUser::find($username);

    if (!$user)
        return HusmusenError::SendError(400, 'ERR_USER_NOT_FOUND', 'There is no user with that username!');

    if (!$user->delete())
        return HusmusenError::SendError(500, 'ERR_DATABASE_ERROR', 'There was an error deleting that user!');

    HusmusenLog::write(
        'Database',
        sprintf(
            "Admin '%s' deleted the user '%s'!",
            request()->query('auth_username'),
            $username,
        )
    );

    return response()->json([$username]);
})->middleware('auth:admin');

Route::post('/auth/change_password', function (Request $request) {
    $current_password = $request->input('currentPassword');
    $new_password = $request->input('newPassword');

    if (!$current_password)
        return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'currentPassword'.");

    if (!$new_password)
        return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'newPassword'.");

    // This will be safe, it will always return a valid user.
    // The middleware will make sure that there's never a non-logged-in user here.
    // By extent, this always returns an existing user.
    $token = $request->header('Husmusen-Access-Token');
    $decoded_token = HusmusenUser::decode_token($token);
    $who = HusmusenUser::find($decoded_token->sub);

    $password_matches = Hash::check($current_password, $who->password);
    if (!$password_matches)
        return HusmusenError::SendError(401, 'ERR_INVALID_PASSWORD', "Your provided 'currentPassword' is not correct!");

    DB::table('husmusen_users')
        ->where('username', $who->sub)
        ->update(['password' => Hash::make($new_password)]);

    return response()->json(['username' => $who->username, 'password' => ($new_password)]);
})->middleware('auth:user');

if (env('APP_DEBUG', false)) {
    Route::post('/auth/debug_admin_creation', function (Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        if (!$username)
            return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'username'.");

        if (!$password)
            return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'password'.");

        $user = HusmusenUser::find($username);

        if ($user)
            return HusmusenError::SendError(400, 'ERR_ALREADY_EXISTS', 'That user already exists!');

        HusmusenUser::create([
            'username' => $username,
            'password' => Hash::make($password),
            'isAdmin' => true,
        ]);
    });
}

/*
 * PROTECTED ROUTES
 */
Route::post('/1.0.0/item/new', function () {
    $itemToCreate = HusmusenItem::from_array_data(request()->all());
    $saveSucceded = $itemToCreate->save();

    if (!$saveSucceded) {
        return HusmusenError::SendError(500, 'ERR_DATABASE_ERROR', 'Something went wrong while saving the item!');
    }

    HusmusenLog::write(
        'Database',
        sprintf(
            "%s '%s' created item with ID '%s'!",
            request()->query('is_admin') ? 'Admin' : 'User',
            request()->query('auth_username'),
            $itemToCreate->itemID
        )
    );

    return json_encode($itemToCreate);
})->middleware('auth:user')->middleware('yaml_parser');

Route::post('/1.0.0/item/edit', function () {
    $itemID = request()->input('itemID');
    $itemToUpdate = HusmusenItem::find($itemID);

    if (!$itemToUpdate) {
        return HusmusenError::SendError(404, 'ERR_OBJECT_NOT_FOUND', "The item you're trying to edit does not exist!");
    }

    $newItemData = request()->input('newItemData');
    $saveSucceded = HusmusenItem::update_from_array_data($itemToUpdate, $newItemData);

    if (!$saveSucceded) {
        return HusmusenError::SendError(500, 'ERR_DATABASE_ERROR', 'Something went wrong while saving the item!');
    }

    HusmusenLog::write(
        'Database',
        sprintf(
            "%s '%s' updated item with ID '%s'!",
            request()->query('is_admin') ? 'Admin' : 'User',
            request()->query('auth_username'),
            $itemToUpdate->itemID
        )
    );

    // TODO: I don't know if this data will be changed or not.
    // Depends on if it is passed by reference or value... (Check!)
    return json_encode($itemToUpdate);
})->middleware('auth:user')->middleware('yaml_parser');

Route::post('/1.0.0/item/mark', function (Request $request) {
    $item_id = $request->input('itemID');
    $reason = $request->input('reason');

    if (!$item_id)
        return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'itemID'.");

    if (!$reason)
        return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'reason'.");

    $item = HusmusenItem::find($item_id);
    if (!$item)
        return HusmusenError::SendError(404, 'ERR_ITEM_NOT_FOUND', 'It appears this item does not exist.');

    $item->isExpired = true;
    $item->expireReason = $request->input('reason');

    $save_succeded = $item->save();
    if (!$save_succeded)
        return HusmusenError::SendError(500, 'ERR_DATABASE_ERROR', 'Something went wrong while saving the item!');

    HusmusenLog::write(
        'Database',
        sprintf(
            "%s '%s' marked item with ID '%s' as expired with the reason '%s'!",
            request()->query('is_admin') ? 'Admin' : 'User',
            request()->query('auth_username'),
            $item_id,
            $reason,
        )
    );

    return response()->json($item);
})->middleware('auth:user');

Route::post('/1.0.0/file/new', function (Request $request) {
    $fileToCreate = HusmusenFile::from_array_data(request()->all());
    $saveSucceded = $fileToCreate->save();

    if (!$saveSucceded) {
        return HusmusenError::SendError(500, 'ERR_DATABASE_ERROR', 'Something went wrong while saving the file metadata!');
    }

    $request->file('avatar')->storeAs(
        'files', $fileToCreate->fileID
    );

    HusmusenLog::write(
        'Database',
        sprintf(
            "%s '%s' created file with ID '%s'!",
            request()->query('is_admin') ? 'Admin' : 'User',
            request()->query('auth_username'),
            $fileToCreate->fileID
        )
    );

    return json_encode($fileToCreate);

})->middleware('auth:user')->middleware('yaml_parser');

Route::post('/1.0.0/file/edit/', function () {
    $fileID = request()->input('fileID');
    $fileToUpdate = HusmusenFile::find($fileID);

    if (!$fileToUpdate) {
        return HusmusenError::SendError(404, 'ERR_OBJECT_NOT_FOUND', "The item you're trying to edit does not exist!");
    }

    $newFileData = request()->input('newFileData');
    $saveSucceded = HusmusenItem::update_from_array_data($fileToUpdate, $newFileData);

    if (!$saveSucceded) {
        return HusmusenError::SendError(500, 'ERR_DATABASE_ERROR', 'Something went wrong while saving the file!');
    }

    HusmusenLog::write(
        'Database',
        sprintf(
            "%s '%s' updated file with ID '%s'!",
            request()->query('is_admin') ? 'Admin' : 'User',
            request()->query('auth_username'),
            $fileToUpdate->fileID
        )
    );

    // TODO: I don't know if this data will be changed or not.
    // Depends on if it is passed by reference or value... (Check!)
    return json_encode($fileToUpdate);
})->middleware('auth:user')->middleware('yaml_parser');

Route::post('/1.0.0/file/delete', function (Request $request) {
    $id = $request->input('fileID');
    if (!$id) {
        return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'fileID'.");
    }

    $file = HusmusenFile::find($id);
    if (!$file) {
        return HusmusenError::SendError(400, 'ERR_FILE_NOT_FOUND', "That item doesn't exist!");
    }

    HusmusenFile::destroy($id);

    // FIXME: remove related file data in `data/files`.

    HusmusenLog::write('Database', sprintf("%s '%s' deleted item with ID '%d'!", (request()->query('is_admin')) ? 'Admin' : 'User', request()->query('auth_username'), $id));
    return $file;
})->middleware('auth:user')->middleware('yaml_parser');

/*
 * PROTECTED ROUTES (ADMIN ONLY)
 */
Route::post('/db_info', function () {})->middleware('auth:admin');
Route::post('/1.0.0/item/delete', function (Request $request) {
    $id = $request->input('itemID');
    if (!$id) {
        return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'itemID'.");
    }

    $item = HusmusenItem::find($id);
    if (!$item) {
        return HusmusenError::SendError(400, 'ERR_OBJECT_NOT_FOUND', "That item doesn't exist!");
    }

    HusmusenItem::destroy($id);  // TODO: remove related files.
    HusmusenLog::write('Database', sprintf("%s '%s' deleted item with ID '%d'!", (request()->query('is_admin')) ? 'Admin' : 'User', request()->query('auth_username'), $id));
    return $item;
})->middleware('auth:admin');

Route::post('/1.0.0/keyword', function () {})->middleware('auth:admin');

Route::get('/1.0.0/log/get', function () {
    $reverse = request()->query('reverse', 'on');
    if (in_array($reverse, ['on', '1', 'true'])) {
        return response()->json(HusmusenLog::orderByDesc('timestamp')->get());
    }
    return response()->json(HusmusenLog::orderBy('timestamp')->get());
})->middleware('auth:admin');
