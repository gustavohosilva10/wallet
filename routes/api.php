<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domain\Bracket\Controllers\BracketController;
use App\Domain\Person\Controllers\PersonController;
use App\Domain\Contact\Controllers\ContactController;
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
Route::post('/validate-brackets', [BracketController::class, 'validateBrackets']);

Route::get('people', [PersonController::class, 'getPersons']);
Route::post('people', [PersonController::class, 'storePerson']);
Route::get('people/{person}', [PersonController::class, 'showPerson']);
Route::put('people/{person}', [PersonController::class, 'updatePerson']);
Route::delete('people/{person}', [PersonController::class, 'destroyPerson']);

Route::get('contacts', [ContactController::class, 'getContacts']);
Route::post('contacts', [ContactController::class, 'storeContact']);
Route::get('contacts/{idContact}', [ContactController::class, 'showContact']);
Route::put('contacts/{contact}', [ContactController::class, 'updateContact']);
Route::delete('contacts/{contact}', [ContactController::class, 'destroyContact']);