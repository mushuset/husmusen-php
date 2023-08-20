<?php

use App\Models\HusmusenDBInfo;
use App\Models\HusmusenFile;
use App\Models\HusmusenItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Redirect::to('/app');
});

Route::get('/app', function (Request $request) {
    $queries = $request->query();
    return view('landing', ['queries' => $queries]);
});

Route::get('/app/search', function (Request $request) {
    $queries = $request->query();
    return view('search', ['queries' => $queries]);
});

// TODO: Also search for files related to the item.
Route::get('/app/item/{id}', function (string $id) {
    // Make sure the ID is valid; only consisting of numbers.
    if (!preg_match('/^\d+$/', $id)) {
        return view('item', ['err' => 'Invalid ID!']);
    }

    // Search for the item in the database.
    $item = HusmusenItem::find($id);

    // Check if the item doesn't exist.
    if ($item == null) {
        return view('item', ['err' => 'Item not found!']);
    }

    // Convert the JSON data into associative arrays.
    $item->itemData = json_decode($item->itemData);
    $item->customData = json_decode($item->customData);

    return view('item', ['item' => $item]);
});

Route::get('/app/file/{id}', function (string $id) {
    $file = HusmusenFile::find($id);
    return view('file', ['file' => $file]);
});

Route::get('/app/keywords', function () {
    return view('keywords');
});

Route::get('/app/db_info', function () {
    $db_info = HusmusenDBInfo::Default();
    return view('db_info', ['db_info' => $db_info]);
});

Route::get('/app/about', function () {
    return view('about');
});

Route::get('/app/login', function () {
    return view('login');
});

Route::get('/app/control_panel', function () {
    return view('control_panel.index');
});

Route::get('/app/control_panel/new_item', function () {
    return view('control_panel.new_item');
});

Route::get('/app/control_panel/edit_item', function () {
    return view('control_panel.edit_item');
});

Route::get('/app/control_panel/edit_file', function () {
    return view('control_panel.edit_file');
});

Route::get('/app/control_panel/edit_keywords', function () {
    return view('control_panel.edit_keywords');
});

Route::get('/app/control_panel/log', function () {
    return view('control_panel.log');
});

// FIXME: This should be read from an environment variable!
$DEBUG = false;
if ($DEBUG == true) {
    Route::get('/app/setup', function () {
        return view('item');
    });
}
