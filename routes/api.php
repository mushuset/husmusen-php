<?php

use App\Models\HusmusenDBInfo;
use App\Models\HusmusenError;
use App\Models\HusmusenFile;
use App\Models\HusmusenItem;
use App\Models\HusmusenLog;
use App\Models\HusmusenUser;
use Illuminate\Hashing\Argon2IdHasher;
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

    if (!$username) {
        return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'username'.");
    }

    if (!$password) {
        return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'password'.");
    }

    $user = HusmusenUser::find($username);

    if (!$user) {
        return HusmusenError::SendError(500, 'ERR_USER_NOT_FOUND', 'There was an error looking up the user!');
    }

    $argon2 = new Argon2IdHasher();
    $pass_is_valid = $argon2->check($password, $user->password);

    if (!$pass_is_valid) {
        return HusmusenError::SendError(400, 'ERR_INVALID_PASSWORD', 'Incorrect password.');
    }

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

Route::post('/auth/new', function (Request $request) {})->middleware('auth:admin');

Route::post('/auth/change_password', function (Request $request) {})->middleware('auth:user');

if (env('APP_DEBUG', false)) {
    Route::post('/auth/debug_admin_creation', function (Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        if (!$username) {
            return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'username'.");
        }

        if (!$password) {
            return HusmusenError::SendError(400, 'ERR_MISSING_PARAMETER', "You must specify 'password'.");
        }

        $user = HusmusenUser::find($username);

        if ($user) {
            return HusmusenError::SendError(400, 'ERR_ALREADY_EXISTS', 'That user already exists!');
        }

        HusmusenUser::create([
            'username' => $username,
            'password' => $password,
            'isAdmin' => true,
        ]);
    });
}

/*
 * PROTECTED ROUTES
 */
Route::post('/1.0.0/item/new', function () {
    HusmusenLog::write('Database', sprintf("%s '%s' created item with ID '%d'!", (request()->query('is_admin')) ? 'Admin' : 'User', request()->query('auth_username')), 99);
})->middleware('auth:user');
Route::post('/1.0.0/item/edit/{id}', function () {})->middleware('auth:user');
Route::post('/1.0.0/item/mark/{id}', function () {})->middleware('auth:user');
Route::post('/1.0.0/file/new', function () {})->middleware('auth:user');
Route::post('/1.0.0/file/edit/{id}', function () {})->middleware('auth:user');
Route::post('/1.0.0/file/delete/{id}', function () {})->middleware('auth:user');

/*
 * PROTECTED ROUTES (ADMIN ONLY)
 */
Route::post('/db_info', function () {})->middleware('auth:admin');
Route::post('/1.0.0/item/delete/{id}', function () {})->middleware('auth:admin');
Route::post('/1.0.0/keyword', function () {})->middleware('auth:admin');

Route::get('/1.0.0/log/get', function () {
    HusmusenLog::write('Auth', sprintf("Admin '%s' accessed the log!", request()->query('auth_username')));
    return response()->json(HusmusenLog::orderByDesc('timestamp')->get());
})->middleware('auth:admin');
