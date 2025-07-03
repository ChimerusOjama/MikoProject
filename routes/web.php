<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirstController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// GROUPE : FirstController (public routes)
Route::controller(FirstController::class)->group(function () {
    Route::get('/', 'index')->name('uHome');
    Route::get('/Nos_formations', 'formListing')->name('listing');
    Route::get('/Reserver_votre_place/form={id}', 'formSingle')->name('inscription');
    Route::post('/Inscription', 'formInsc')->name('inscForm');
    Route::get('/A_propos', 'aboutView')->name('about');
    Route::get('/Contact', 'contactView')->name('contact');
    Route::post('/afficher-confirmation/{id}', 'afficherConfirmation');
    Route::post('/Annuler_reservation/{id}', 'annulerRes')->name('annuler.inscription');
});

// GROUPE : FirstController (routes protégées)
Route::middleware(['auth', 'verified'])->controller(FirstController::class)->group(function () {
    Route::get('/home', 'redirect')->name('home');
    Route::get('/Mon_tableau_de_bord', 'uAdmin')->name('uAdmin');
    Route::get('/Mes_formations', 'uFormation')->name('uFormation');
    Route::get('/Mon_profil_utilisateur', 'uProfile')->name('uProfile');
    Route::get('/Support', 'uSupport')->name('uSupport');
    Route::post('/logout', 'uLogout')->name('uLogout');
});

// GROUPE : AdminController (routes admin)
Route::middleware(['auth', 'verified'])->controller(AdminController::class)->group(function () {
    Route::get('/formations', 'allForm')->name('allForm');
    Route::get('/nouvelle_formation', 'newForm')->name('newForm');
    Route::post('/Insertion', 'storeForm')->name('storeForm');
    Route::get('/Reservations', 'reserveView')->name('allreserv');
    Route::get('/Accepter_reservation/inscription={id}', 'accepterRes');
    Route::get('/Rejeter_reservation/inscription={id}', 'rejeterRes');
    Route::get('/Supprimer_formation/foramtion={id}', 'supForm');
    Route::get('/Modifier_formation/foramtion={id}', 'updateView');
    Route::post('/Mise_a_jour/formation={id}', 'updateForm')->name('updateForm');
});

// ✅ Route de test mail (isolée)
Route::get('/test-mail', [MainController::class, 'testMail'])->name('testMail');



// Route::get('/test-mail', function () {
//     Mail::to('berchebaisrael@gmail.com')->send(new TestMail());
//     return 'E-mail envoyé !';
// });




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

