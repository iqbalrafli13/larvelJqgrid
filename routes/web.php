<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});
Route::get('/data/export_modal', function(Request $request){
    return view('modal.report',["total"=>$request->total]);
});

Route::get('/data/report_modal', function(Request $request){
    return view('modal.report',["total"=>$request->total]);
});

Route::get('/data/create', [TransactionController::class,'create']);
Route::get('/data/edit/{id}', [TransactionController::class,'edit']);
Route::get('/data/delete/{id}', [TransactionController::class,'delete']);

Route::get('/data/export',[ReportController::class,'export']);
Route::get('/data/report',[ReportController::class,'report']);
Route::get('/data/data_report',[ReportController::class,'data_report']);
// Route::get('/', function () {
//     return view('welcome');
// });
