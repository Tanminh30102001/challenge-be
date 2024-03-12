<?php

use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);
Route::get('/getAllUser',[UserController::class,'index']);
Route::post('/addTask',[TaskController::class,'store']);
Route::patch('/approveUser/{id}',[UserController::class,'approveUser']);
Route::get('/allTask',[TaskController::class,'index']);
Route::get('/getDetailsTask/{id}',[TaskController::class,'getDetailsTask']);
Route::get('/getStatus',[StatusController::class,'getAllStatus']);
Route::get('/getType',[StatusController::class,'getAllType']);
Route::get('/getPriority',[StatusController::class,'getAllPriority']);
Route::patch('/updateTask/{id}',[TaskController::class,'update']);
Route::get('/getTaskByUser/{id}',[TaskController::class,'show']);
Route::post('/filterAllTask',[TaskController::class,'filterAllTask']);
Route::post('/fillterWithUser/{id}',[TaskController::class,'fillterWithUser']);
Route::post('/search',[TaskController::class,'search']);
Route::post('/searchWithUser/{id}',[TaskController::class,'searchWithUser']);
Route::get('/staticTask',[TaskController::class,'reportTaskStatic']);