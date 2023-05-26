<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardsController;

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
    return view('welcome');
});

// 라우트에서 유저 정보 정의해서 컨트롤러 이후 뷰로 데이터 전송
// 컨트롤러 없어도 라우트에 정의할 수 있음 

Route::resource('/boards', BoardsController::class);
// 리소스는 자동으로 값 부여해줌

