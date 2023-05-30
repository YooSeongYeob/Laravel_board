<?php
//--------------------------------------------------
// 프로젝트명 : laravel_board
// 디렉터리   : controllers
// 파일명     : UserController.php
// 이력       : v001 0530 SY.Yoo new              
//--------------------------------------------------


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardsController;
use App\Http\Controllers\UserController;

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

// Board
Route::resource('/boards', BoardsController::class);
// 리소스는 자동으로 값 부여해줌

// Route::patch('/boards', [BoardsController::class, 'update'])->name('boards.update');
// Laravel에서 업데이트의 대한 메서드로는 Patch 또는 Put을 권장합니다.

// 로그인은 겟임
// 여기서 users가 빠지게 되면 다른 곳에 다 users가 빠지게 된다

// Users
Route::get('/users/login', [UserController::class, 'login'])->name('users.login'); 
Route::post('/users/loginpost', [UserController::class, 'loginpost'])->name('users.login.post'); 
Route::get('/users/registration', [UserController::class, 'registration'])->name('users.registration'); 
Route::post('/users/registrationpost', [UserController::class, 'registrationpost'])->name('users.registration.post'); 


// 리소스가 레스트풀 Api로 맞춰짐
// 메소드로 구분 
// 겟은 화면에 띄울 때
// 포스트는 데이터베이스에 갱신을 할 때