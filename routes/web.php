<?php

use App\Models\HusmusenDBInfo;
use App\Models\HusmusenFile;
use App\Models\HusmusenItem;
use App\Models\HusmusenItemType;
use App\Models\HusmusenKeyword;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Yaml\Yaml;

require __DIR__.'/setup.php';

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "web" middleware group. Make something great!
 * |
 */

Route::get('/', function () {
    return Redirect::to('/app');
});

Route::get('/app', function (Request $request) {
    $queries = $request->all();

    return view('landing', ['queries' => $queries]);
});

Route::get('/app/search', function (Request $request) {
    $queries = $request->all();

    return view('search', ['queries' => $queries]);
});

Route::get('/app/items', function () {
    // Get all items.
    $items = HusmusenItem::all();

    return view('all_items', ['items' => $items]);
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
    if (!$item) {
        return view('item', ['err' => 'Item not found!']);
    }

    // Convert the JSON data into associative arrays.
    // $item->itemData = json_decode($item->itemData);
    // $item->customData = json_decode($item->customData);

    return view('item', ['item' => $item]);
});

Route::get('/app/file/{id}', function (string $id) {
    $file = HusmusenFile::find($id);

    if (!$file) {
        return view('file', ['err' => 'File not found!']);
    }

    return view('file', ['file' => $file]);
});

Route::get('/app/keywords', function () {
    return view('keywords', ['keywords' => HusmusenKeyword::get_all()]);
});

Route::get('/app/db_info', function () {
    $db_info = HusmusenDBInfo::get_db_info();

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

Route::get('/app/control_panel/new_item', function (Request $request) {
    // type validation
    if (!in_array($request->query('type'), HusmusenItem::$valid_types)) {
        $request->instance()->query->set('type', 'Book');
    }

    $next_item_id = HusmusenItem::get_next_item_id();
    $keywords_for_type = HusmusenKeyword::get_all_by_types([HusmusenItemType::from($request->query('type'))]);

    return view('control_panel.new_item', ['next_item_id' => $next_item_id, 'keywords' => $keywords_for_type]);
});

Route::get('/app/control_panel/edit_item', function (Request $request) {
    $id = $request->query('itemID');
    if (!$id) {
        return view('control_panel.edit_item', ['err' => 'No itemID specified.']);
    }

    $item = HusmusenItem::find($id);
    if (!$item) {
        return view('control_panel.edit_item', ['err' => 'Item not found.']);
    }

    $item = $item->toArray();

    $item_as_yaml = Yaml::dump($item, 2, 4);

    return view('control_panel.edit_item', ['itemID' => $id, 'itemAsYAML' => $item_as_yaml]);
});

Route::get('/app/control_panel/edit_file', function (Request $request) {
    $id = $request->query('fileID');
    if (!$id) {
        return view('control_panel.edit_file', ['err' => 'No fileID specified.']);
    }

    $file = HusmusenFile::find($id);
    if (!$file) {
        return view('control_panel.edit_file', ['err' => 'File not found.']);
    }

    $file = $file->toArray();

    $file_as_yaml = Yaml::dump($file, 2, 4);

    return view('control_panel.edit_file', ['fileID' => $id, 'fileAsYAML' => $file_as_yaml]);
});

Route::get('/app/control_panel/edit_keywords', function () {
    return view('control_panel.edit_keywords', ['keywordsAsText' => HusmusenKeyword::get_all_as_edit_friendly_format()]);
});

Route::get('/app/control_panel/edit_dbinfo', function () {
    $db_info = HusmusenDBInfo::get_db_info();
    $db_info_as_yaml = Yaml::dump((array) $db_info, 2, 4);

    return view('control_panel.edit_dbinfo', ['dbInfoAsYAML' => $db_info_as_yaml]);
});

Route::get('/app/control_panel/log', function () {
    return view('control_panel.log');
});
