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

Route::get('/1.0.0/item/info/{id}', function (Request $request, string $id) {
    return HusmusenItem::find($id);
});

Route::get('/1.0.0/file/all', function (Request $request) {
    return HusmusenFile::all();
});

Route::get('/1.0.0/file/info/{id}', function (Request $request, string $id) {
    return HusmusenFile::find($id);
});