<?php

use App\Models\HusmusenDBInfo;
use App\Models\HusmusenError;
use App\Models\HusmusenFile;
use App\Models\HusmusenItem;
use App\Models\HusmusenUser;
use Illuminate\Hashing\Argon2IdHasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
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

// TODO: This should load from a file instead!
Route::get('/db_info', function () {
    $db_info = HusmusenDBInfo::Default();
    return response()->json($db_info);
});

// TODO: This should load from a file instead!
Route::get('/db_info/version', function () {
    $db_info = HusmusenDBInfo::Default();
    return $db_info->protocolVersion;
});

// TODO: This should load from a file instead!
Route::get('/db_info/versions', function () {
    $db_info = HusmusenDBInfo::Default();
    return implode(',', $db_info->protocolVersions);
});

// FIXME: This isn't really working, and I am taking a break from it.
Route::get('/1.0.0/item/search', function (Request $request) {
    $VALID_SORT_FIELDS = ['name', 'relevance', 'lastUpdated', 'addedAt', 'itemID'];

    $types = $request->query('types', '');
    $freetext = $request->query('freetext', '');
    $keywords = $request->query('keywords', '');
    $keyword_mode = $request->query('keyword-mode', 'OR');
    $order_by = $request->query('sort', 'name');
    $reverse = $request->query('reverse', 'false');

    $types_as_array = preg_split('/,/', $types);

    /**
     * This formula figures out if the results should be reversed or not.
     * The complexity is needed because the 'relevance' search option works
     * the other way around compared to all other sorting otions.
     */
    $should_reverse_order = $order_by == 'relevance'
        ? ($reverse == '1' || $reverse == 'on' || $reverse == 'true' ? 'ASC' : 'DESC')
        : ($reverse == '1' || $reverse == 'on' || $reverse == 'true' ? 'DESC' : 'ASC');

    $items = DB::table('husmusen_items')
        ->orWhereFullText('name', $freetext)
        ->orWhereFullText('description', $freetext)
        ->whereIn('type', $types_as_array)
        ->orderBy($order_by, $should_reverse_order)
        ->get();

    return $items;
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

    return response()->json([
        'token' => $token,
        'validUntil' => date('c', $valid_until)  // Format date as per ISO 8601.
    ]);
});

Route::post('/auth/who');
Route::post('/auth/new');
Route::post('/auth/change_password');

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
Route::post('/1.0.0/item/new');
Route::post('/1.0.0/item/edit/{id}');
Route::post('/1.0.0/item/mark/{id}');

Route::post('/1.0.0/file/new');
Route::post('/1.0.0/file/edit/{id}');
Route::post('/1.0.0/file/delete/{id}');

/*
 * PROTECTED ROUTES (ADMIN ONLY)
 */
Route::post('/db_info');
Route::post('/1.0.0/item/delete/{id}');
Route::post('/1.0.0/keyword');

Route::get('/1.0.0/log/get');
