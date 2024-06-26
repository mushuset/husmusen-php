<?php

use App\Models\HusmusenDBInfo;
use App\Models\HusmusenFile;
use App\Models\HusmusenItem;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Yaml\Yaml;

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
    return view('file', ['file' => $file]);
});

Route::get('/app/keywords', function () {
    return view('keywords');
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

Route::get('/app/control_panel/new_item', function () {
    // type validation
    if (!in_array(request()->query('type'), HusmusenItem::$valid_types)) {
        request()->instance()->query->set('type', 'Book');
    }

    $next_item_id = HusmusenItem::get_next_item_id();
    return view('control_panel.new_item', ['next_item_id' => $next_item_id]);
});

Route::get('/app/control_panel/edit_item', function () {
    $id = request()->query('itemID');
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

Route::get('/app/control_panel/edit_file', function () {
    return view('control_panel.edit_file');
});

Route::get('/app/control_panel/edit_keywords', function () {
    return view('control_panel.edit_keywords');
});

Route::get('/app/control_panel/log', function () {
    return view('control_panel.log');
});

if (env('APP_DEBUG', false)) {
    Route::get('/app/setup', function () {
        return view('item');
    });
}
