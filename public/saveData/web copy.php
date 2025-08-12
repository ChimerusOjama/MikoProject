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
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [FirstController::class, 'redirect'])->name('home');
    Route::post('/logout-user', [FirstController::class, 'uLogout'])->name('logout-user');
});

// GROUPE : Utilisateur simple (usertype = user)
// Route::prefix('MikoFormation')->middleware(['auth', 'verified', 'isUser'])->controller(FirstController::class)->group(function () {
//     Route::get('/acceuil', 'index')->name('index');
//     Route::get('/dashboard', 'uAdmin')->name('uAdmin');
//     Route::get('/Mes_formations', 'uFormation')->name('uFormation');
//     Route::post('/Annuler_reservation/{id}', 'annulerRes')->name('annuler.inscription');
//     Route::get('/Mon_profil_utilisateur', 'uProfile')->name('uProfile');
//     Route::get('/Support', 'uSupport')->name('uSupport');
//     Route::get('/checkout/{inscriptionId}', 'checkout')->name('checkout');
//     Route::get('/payment/success', 'success')->name('payment.success');
//     Route::get('/payment/cancel', 'cancel')->name('payment.cancel');
// });
Route::middleware(['auth', 'verified', 'isUser'])->controller(FirstController::class)->group(function () {
    Route::get('/acceuil', 'index')->name('index');
    Route::get('/dashboard', 'uAdmin')->name('uAdmin');
    Route::get('/Mes_formations', 'uFormation')->name('uFormation');
    Route::post('/Annuler_reservation/{id}', 'annulerRes')->name('annuler.inscription');
    Route::get('/Mon_profil_utilisateur', 'uProfile')->name('uProfile');
    Route::get('/Support', 'uSupport')->name('uSupport');
    Route::get('/checkout/{inscriptionId}', 'checkout')->name('checkout');
    Route::get('/payment/success', 'success')->name('payment.success');
    Route::get('/payment/cancel', 'cancel')->name('payment.cancel');
});

// GROUPE : Administrateur (usertype = admin)
Route::prefix('admin')->middleware(['auth', 'verified', 'isAdmin'])->controller(AdminController::class)->group(function () {
    // Tableau de bord
    // Route::get('/dashboard', function () {
    //     return view('admin.index');
    // })->name('admin.dashboard');
    Route::get('/dashboard', 'aIndex')->name('admin.dashboard');

    // Formations
    Route::get('/Liste_formations', 'allForm')->name('allForm');
    Route::get('/nouvelle_formation', 'newForm')->name('newForm');
    Route::post('/Insertion', 'storeForm')->name('storeForm');
    Route::get('/Supprimer_formation/formation={id}', 'supForm')->name('supForm');
    Route::get('/Modifier_formation/formation={id}', 'updateView')->name('updateView');
    Route::post('/Mise_a_jour/formation={id}', 'updateForm')->name('updateForm');
    Route::get('/Accepter_reservation/inscription={id}', 'accepterRes')->name('accepterRes');
    Route::get('/Rejeter_reservation/inscription={id}', 'rejeterRes')->name('rejeterRes');
    
    // Inscriptions
    Route::get('/Nouvelle_inscription', 'inscView')->name('inscView');
    Route::post('/Insertion_inscription', 'storeInsc')->name('storeInsc');
    Route::get('/inscriptions', 'inscriptions')->name('admin.inscriptions'); // Simplifié
    
    // Utilisateurs
    Route::get('/Liste_utilisateurs', 'usersView')->name('allUsers');
    Route::get('/nouvel_utilisateur', 'newUser')->name('newUser');
    Route::post('/Insertion_utilisateur', 'storeUser')->name('storeUser');
    Route::get('/Supprimer_utilisateur/utilisateur={id}', 'supUser')->name('supUser');
    Route::get('/Modifier_utilisateur/utilisateur={id}', 'updateUserView')->name('updateUserView');
    Route::post('/Mise_a_jour/utilisateur={id}', 'updateUser')->name('updateUser');
    
    // Paiements
    Route::get('/Liste_paiements', 'allPayments')->name('allPayments');
    Route::get('/nouveau_paiement', 'newPayment')->name('newPayment');
    Route::post('/Insertion_paiement', 'storePayment')->name('storePayment');
    Route::get('/Supprimer_paiement/paiement={id}', 'supPayment')->name('supPayment');
    Route::get('/Modifier_paiement/paiement={id}', 'updatePaymentView')->name('updatePaymentView');
    Route::post('/Mise_a_jour/paiement={id}', 'updatePayment')->name('updatePayment');
    
    // Logout
    Route::post('/logout', 'logout')->name('aLogout');
});

// Route test (facultative)
Route::get('/test-mail', [MainController::class, 'testMail'])->name('testMail');