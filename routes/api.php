<?php

use App\Models\HusmusenFile;
use App\Models\HusmusenItem;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
 * PUBLIC ROUTES
 */

// TODO: This should *NOT* be hard-coded!
Route::get('/db_info', function () {
    return [
        'protocolVersion' => '1.0.0',
        'protocolVersions' => ['1.0.0', '0.8.0'],
        'supportedInputFormats' => ['YAML', 'JSON'],
        'supportedOutputFormats' => ['YAML', 'JSON'],
        'instanceName' => 'Husmusen på Museum',
        'museumDetails' => [
            'name' => 'Museum',
            'description' => 'Ett helt vanligt museum.',
            'address' => 'Gatanvägen 4',
            'location' => 'Kungshamn',
            'coordinates' => '0°0′0″ N, 25°0′0″ W',
            'website' => 'https://example.com'
        ]
    ];
});

// TODO: This should *NOT* be hard-coded!
Route::get('/db_info/version', function () {
    return '1.0.0';
});

// TODO: This should *NOT* be hard-coded!
Route::get('/db_info/versions', function () {
    return '1.0.0';
});

Route::get('/1.0.0/item/search', function (Request $request) {
    $VALID_SORT_FIELDS = ['name', 'relevance', 'lastUpdated', 'addedAt', 'itemID'];

    $types = $request->query('types', '');
    $freetext = $request->query('freetext', '');
    $keywords = $request->query('keywords', '');
    $keyword_mode = $request->query('keyword-mode', 'OR');
    $orderBy = $request->query('sort', 'name');
    $reverse = $request->query('reverse', 'false');

    /**
     * This formula figures out if the results should be reversed or not.
     * The complexity is needed because the 'relevance' search option works
     * the other way around compared to all other sorting otions.
     */
    $shouldReverseOrder = $orderBy == 'relevance'
        ? ($reverse == '1' || $reverse == 'on' || $reverse == 'true' ? 'ASC' : 'DESC')
        : ($reverse == '1' || $reverse == 'on' || $reverse == 'true' ? 'DESC' : 'ASC');

    $items = DB::table('husmusen_items')
        ->orWhereFullText('name', $freetext)
        ->orWhereFullText('description', $freetext)
        ->orderBy($orderBy, $shouldReverseOrder)
        ->get();

    return $items;
});

Route::get('/1.0.0/item/info/{id}', function (string $id) {
    return HusmusenItem::find($id);
});

Route::get('/1.0.0/file/get/{id}', function (string $id) {
    // TODO: Return file data!
    return 'NOT IMPLEMENTED';
});

Route::get('/1.0.0/file/info/{id}', function (string $id) {
    return HusmusenFile::find($id);
});

Route::get('/1.0.0/keyword', function () {
    return 'NOT IMPLEMENTED';
});

/*
 * LOG IN / AUTH SYSTEM
 */
Route::post('/auth/login');
Route::post('/auth/who');
Route::post('/auth/new');
Route::post('/auth/change_password');

// TODO: Get this from an environment variable instead!
$IS_DEBUG_MODE = false;
if ($IS_DEBUG_MODE) {
    Route::post('/api/auth/debug_admin_creation');
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
