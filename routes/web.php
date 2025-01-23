<?php

use App\Events\MainDashboardRefreshEvent;
use App\Events\MyBroadcastEvent;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiceController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\SnakeBoardController;
use App\Http\Middleware\EnsureAuthIsValid;
use Illuminate\Support\Facades\Route;

Route::middleware(EnsureAuthIsValid::class)->group(function () {

    Route::get('/dices', [DiceController::class, 'ShowDicePage'])->name('dicepage');

    Route::post('/post/rmNumber', [DiceController::class, 'getRandomNumber'])->name('postrmNumber');

});

Route::get('/create/account', [AuthController::class, 'SignUp'])->name('SignUp');
Route::post('/login/account', [AuthController::class, 'Login'])->name('login');

Route::post('/answer/correct', [QuestionsController::class, 'QuestionCorrect'])->name('question.correct');
Route::post('/answer/worng', [QuestionsController::class, 'QuestionWrong'])->name('question.wrong');

Route::get('/main/snakeboard', [SnakeBoardController::class, 'SnakeBoard'])->name('snakeboard');

Route::get('/insert/questions', [QuestionsController::class, 'QuestionInsertPage'])->name('question.insert');
Route::post('/insert/questions/new', [QuestionsController::class, 'QuestionAdd'])->name('question.add');

Route::get('/gridview/snakeboard/{top}/{left}', [SnakeBoardController::class, 'GridView'])->name('gridviewsnakeboard');
Route::post('/next/player', [PlayerController::class, 'NextPlayer'])->name('player.next');
Route::post('/sent/player/again', [PlayerController::class, 'SentPermissionAgain'])->name('player.again');
Route::post('/reset/game', [PlayerController::class, 'ResetGame'])->name('player.reset');

Route::get('/refresh/player/page', [PlayerController::class, 'RefreshPage'])->name('player.page.refresh');


Route::get('/Winner-Winner-Water-Dinner/go-back', [PlayerController::class, 'WinnerPageGoBack'])->name('player.back');
