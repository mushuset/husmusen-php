<?php

use Illuminate\Support\Facades\DB;
use App\Models\HusmusenDBInfo;
use App\Models\HusmusenError;
use App\Models\HusmusenFile;
use App\Models\HusmusenItem;
use App\Models\HusmusenUser;
use Illuminate\Hashing\Argon2IdHasher;
use Illuminate\Http\Request;
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
    return join(',', $db_info->protocolVersions);
});

Route::get('/1.0.0/item/search', function (Request $request) {
    // Get all the parameters from the request.
    $types = $request->query('types', '');
    $freetext = $request->query('freetext', '');
    $keywords = $request->query('keywords', '');
    $keyword_mode = $request->query('keyword-mode', 'OR');
    $order_by = $request->query('sort', 'name');
    $reverse = $request->query('reverse', 'false');

    // Filter for only valid types.
    $types_as_array = is_array($types) ? $types : preg_split('/,/', $types);  // Make sure `types` isn't an array already, before splitting it into one.
    $valid_types = array_filter($types_as_array, function ($type) {
        return in_array($type, HusmusenItem::$valid_types);
    });
    $types_sql = "('" . join("','", sizeof($valid_types) != 0 ? $valid_types : HusmusenItem::$valid_types) . "')";

    // Make sure `keyword_mode` is not an array.
    $keyword_mode_as_string = is_array($keyword_mode) ? end($keyword_mode) : $keyword_mode;
    // Make sure it is 'AND' or 'OR'.
    $keyword_mode_sane = $keyword_mode_as_string == 'AND' ? 'AND' : 'OR';

    $keywords_as_array = is_array($types) ? $keywords : preg_split('/,/', $keywords);  // Make sure `keyword` isn't an array already, before splitting it into one.
    // TODO: Validate the keywords.
    $valid_keywords = array_filter($keywords_as_array, function ($keyword) {
        return true;
    });

    // Create keyword SQL; slightly magical. :|
    $keyword_search_sql = $keyword_mode === 'AND'
        // If in "AND-mode", use this magic RegEx created here:
        // This also requires the keywords to be sorted alphabetically.
        ? ($valid_keywords ? "AND keywords RLIKE '(?-i)(?<=,|^)(" . join('(.*,|)', $valid_keywords) . ')(?=,|$)\'' : '')
        // Otherwise, use "OR-mode" with this magic RegEx:
        : ($valid_keywords ? "AND keywords RLIKE '(?-i)(?<=,|^)(" . join('|', $valid_keywords) . ')(?=,|$)\'' : '');

    $VALID_SORT_FIELDS = array('name', 'relevance', 'lastUpdated', 'addedAt', 'itemID');
    // Make sure `order_by` is not an array.
    $order_by_as_string = is_array($order_by) ? end($order_by) : $order_by;
    // Make sure it is 'AND' or 'OR'.
    $order_by_sane = in_array($order_by_as_string, $VALID_SORT_FIELDS) ? $order_by_as_string : 'name';

    /**
     * This formula figures out if the results should be reversed or not.
     * The complexity is needed because the 'relevance' search option works
     * the other way around compared to all other sorting otions.
     */
    $should_reverse_order = $order_by == 'relevance'
        ? ($reverse == '1' || $reverse == 'on' || $reverse == 'true' ? 'ASC' : 'DESC')
        : ($reverse == '1' || $reverse == 'on' || $reverse == 'true' ? 'DESC' : 'ASC');

    // FIXME: Find a way to make sure this is safe and no SQL-incations...
    $sanitized_freetext = $freetext;

    $magic_relevance_sql = "((MATCH(name) AGAINST('$sanitized_freetext' IN BOOLEAN MODE) + 1) * (MATCH(description) AGAINST('$sanitized_freetext' IN BOOLEAN MODE) + 1) - 1) / 3";
    $magic_relevance_search_sql = $freetext != null ? "AND (MATCH(name) AGAINST('$sanitized_freetext' IN BOOLEAN MODE) OR MATCH(description) AGAINST('$sanitized_freetext' IN BOOLEAN MODE))" : '';

    return DB::select("
        SELECT *, ($magic_relevance_sql) AS relevance
            FROM husmusen_items
            WHERE type IN $types_sql
            $keyword_search_sql
            $magic_relevance_search_sql
            ORDER BY $order_by_sane $should_reverse_order
        ");
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

Route::post('/auth/who', function (Request $request) {
    $token = $request->header("Husmusen-Access-Token");
    return HusmusenUser::decode_token($token);
})->middleware('auth:user');

Route::post('/auth/new', function (Request $request) {

})->middleware('auth:user');

Route::post('/auth/change_password', function (Request $request) {

})->middleware('auth:user');

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
Route::post('/1.0.0/item/new', function () { })->middleware('auth:user');
Route::post('/1.0.0/item/edit/{id}', function () { })->middleware('auth:user');
Route::post('/1.0.0/item/mark/{id}', function () { })->middleware('auth:user');
Route::post('/1.0.0/file/new', function () { })->middleware('auth:user');
Route::post('/1.0.0/file/edit/{id}', function () { })->middleware('auth:user');
Route::post('/1.0.0/file/delete/{id}', function () { })->middleware('auth:user');

/*
 * PROTECTED ROUTES (ADMIN ONLY)
 */
Route::post('/db_info', function () { })->middleware('auth:admin');
Route::post('/1.0.0/item/delete/{id}', function () { })->middleware('auth:admin');
Route::post('/1.0.0/keyword', function () { })->middleware('auth:admin');

Route::get('/1.0.0/log/get', function () {
    return 'test';
});
