<?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\LoginController;
    use App\Http\Controllers\ReportController;

    Route::get("/", [LoginController::class, "goLogin"]);
    Route::post("/login", [LoginController::class, "login"]);
    Route::get("/logout", [LoginController::class, "logout"]);
    Route::get("/reports/showList", [ReportController::class, "showList"])->middleware("logincheck");
    Route::get("/reports/goAdd", [ReportController::class, "goAdd"])->middleware("logincheck");
    Route::post("/reports/reportAdd", [ReportController::class, "reportAdd"])->middleware("logincheck");
    Route::get("/reports/showDetail/{reportId}", [ReportController::class, "showDetail"])->middleware("logincheck");
    Route::get("/reports/prepareEdit/{reportId}", [ReportController::class, "prepareEdit"])->middleware("logincheck");
    Route::post("/reports/reportEdit", [ReportController::class, "reportEdit"]);
    Route::get("/reports/confirmDelete/{reportId}", [ReportController::class, "confirmDelete"])->middleware("logincheck");
    Route::post("/reports/reportDelete", [ReportController::class, "reportDelete"])->middleware("logincheck");


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

    // Route::get('/', function () {
    //     return view('welcome');
    // });
