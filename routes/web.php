<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirstController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::post('/logout', [FirstController::class, 'uLogout'])->name('uLogout');
Route::get('/', [FirstController::class, 'index'])->name('uHome');
Route::get('/home', [FirstController::class, 'redirect'])->name('home');
Route::get('/Nos_formations', [FirstController::class, 'formListing'])->name('listing');
Route::get('/Reserver_votre_place/form={id}', [FirstController::class, 'formSingle'])->name('singleForm');
Route::post('/Inscription', [FirstController::class, 'formInsc'])->name('inscForm');
Route::get('/Mes_reservations', [FirstController::class, 'uAdmin'])->name('uAdmin');
Route::get('/A_propos', [FirstController::class, 'aboutView'])->name('aboutView');
Route::get('/Annuler_reservation/inscription={id}', [FirstController::class, 'annulerRes']);

Route::get('/formations', [AdminController::class, 'allForm'])->name('allForm');
Route::get('/nouvelle_formation', [AdminController::class, 'newForm'])->name('newForm');
Route::post('/Insertion', [AdminController::class, 'storeForm'])->name('storeForm');
Route::get('/Reservations', [AdminController::class, 'reserveView'])->name('allreserv');
Route::get('/Accepter_reservation/inscription={id}', [AdminController::class, 'accepterRes']);
Route::get('/Rejeter_reservation/inscription={id}', [AdminController::class, 'rejeterRes']);
Route::get('/Supprimer_formation/foramtion={id}', [AdminController::class, 'supForm']);
Route::get('/Modifier_formation/foramtion={id}', [AdminController::class, 'updateView']);
Route::post('/Mise_a_jour/formation={id}', [AdminController::class, 'updateForm'])->name('updateForm');




// Route::get('/', function () {
//     return view('welcome');
// });
// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

