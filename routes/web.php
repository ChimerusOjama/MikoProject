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

// GROUPE : Routes publiques (FirstController)
Route::controller(FirstController::class)->group(function () {
    Route::get('/', 'index')->name('uHome');
    Route::get('/Nos_formations', 'formListing')->name('listing');
    Route::get('/Reserver_votre_place/form={id}', 'formSingle')->name('inscription');
    Route::post('/Inscription', 'formInsc')->name('inscForm');
    Route::get('/A_propos', 'aboutView')->name('about');
    Route::get('/Contact', 'contactView')->name('contact');
    Route::post('/afficher-confirmation/{id}', 'afficherConfirmation');
});

// GROUPE : Routes partagées (auth + redirect)
Route::middleware(['auth', 'verified'])->get('/home', [FirstController::class, 'redirect'])->name('home');

// GROUPE : Utilisateur simple (usertype = user)
Route::middleware(['auth', 'verified', 'isUser'])->controller(FirstController::class)->group(function () {
    Route::get('/Mon_tableau_de_bord', 'uAdmin')->name('uAdmin');
    Route::get('/Mes_formations', 'uFormation')->name('uFormation');
    Route::post('/Annuler_reservation/{id}', 'annulerRes')->name('annuler.inscription');
    Route::get('/Mon_profil_utilisateur', 'uProfile')->name('uProfile');
    Route::post('/logout', 'uLogout')->name('uLogout');
    Route::get('/Support', 'uSupport')->name('uSupport');
    Route::get('/checkout/{inscriptionId}', 'checkout')->name('checkout');
    Route::get('/payment/success', 'success')->name('payment.success');
    Route::get('/payment/cancel', 'cancel')->name('payment.cancel');
});

// GROUPE : Administrateur (usertype = admin)
Route::middleware(['auth', 'verified', 'isAdmin'])->controller(AdminController::class)->group(function () {
    Route::get('/Liste_formations', 'allForm')->name('allForm');
    Route::get('/nouvelle_formation', 'newForm')->name('newForm');
    Route::post('/Insertion', 'storeForm')->name('storeForm');
    Route::get('/Reservations', 'reserveView')->name('allreserv');
    Route::get('/Accepter_reservation/inscription={id}', 'accepterRes')->name('accepterRes');
    Route::get('/Rejeter_reservation/inscription={id}', 'rejeterRes')->name('rejeterRes');
    Route::get('/Supprimer_formation/foramtion={id}', 'supForm')->name('supForm');
    Route::get('/Modifier_formation/foramtion={id}', 'updateView')->name('updateView');
    Route::post('/Mise_a_jour/formation={id}', 'updateForm')->name('updateForm');
    Route::post('/logout', 'logout')->name('aLogout');
});

// Route test (facultative)
Route::get('/test-mail', [MainController::class, 'testMail'])->name('testMail');
