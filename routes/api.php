<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DetailtransactionController;
use App\Models\Gender;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('data',[TransactionController::class,'index']);
Route::post('data',[TransactionController::class,'store']);
Route::get('data/{id}',[TransactionController::class,'show']);
Route::put('data/{id}',[TransactionController::class,'update']);
Route::post('data/{id}/delete',[TransactionController::class,'destroy']);

Route::get('detail/{id}',[DetailtransactionController::class,'index']);
Route::get('gender',function(Type $var = null){
    return Gender::all();
});