<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirstController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PawaPayController;

use App\Services\PawaPayService;
use App\Models\Paiement;
use Illuminate\Support\Str;


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
    Route::get('/A_propos', 'aboutView')->name('about');
    Route::get('/Contact', 'contactView')->name('contact');
    Route::post('/afficher-confirmation/{id}', 'afficherConfirmation');
});

// GROUPE : Routes partagées (auth + redirect)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [FirstController::class, 'redirect'])->name('home');
});

// GROUPE : Utilisateur simple (usertype = user)
Route::middleware(['auth', 'verified', 'isUser'])->prefix('user')->controller(FirstController::class)->group(function () {
    Route::get('/acceuil', 'index')->name('index');
    Route::post('/Inscription', 'formInsc')->name('inscForm');
    Route::get('/user_dashboard', 'uAdmin')->name('uAdmin');
    Route::get('/Mes_formations', 'uFormation')->name('uFormation');
    Route::post('/Annuler_reservation/{id}', 'annulerRes')->name('annuler.inscription');
    Route::get('/Mon_profil_utilisateur', 'uProfile')->name('uProfile');
    Route::get('/Support', 'uSupport')->name('uSupport');

    //payement routes
    Route::get('/paiement/choix-methode/{inscriptionId}', 'showPaymentMethods')->name('payment.methods');
    Route::post('/paiement/process/{inscriptionId}', 'processPayment')->name('payment.process');
    Route::get('/checkout/{inscriptionId}', 'checkout')->name('checkout');
    Route::get('/payment/verify', 'verifyPayment')->name('payment.verify');
    Route::get('/payment/cancel', 'cancel')->name('payment.cancel');
    Route::get('/payment/expired', 'showLinkExpired')->name('payment.expired');  
    
    //logout
    Route::post('/logout-user', 'uLogout')->name('logout-user');
});

// GROUPE : Administrateur (usertype = admin)
Route::middleware(['auth', 'verified', 'isAdmin'])->prefix('admin')->controller(AdminController::class)->group(function () {
    // Tableau de bord
    Route::get('/dashboard', 'aIndex')->name('admin.dashboard');

    // Formations
    Route::get('/Liste_formations', 'allForm')->name('allForm');
    Route::get('/nouvelle_formation', 'newForm')->name('newForm');
    Route::post('/Insertion', 'storeForm')->name('storeForm');
    Route::get('/Supprimer_formation/formation={id}', 'supForm')->name('supForm');
    Route::get('/Modifier_formation/formation={id}', 'updateView')->name('updateView');
    Route::post('/Mise_a_jour/formation={id}', 'updateForm')->name('updateForm');
    Route::get('/archiver_formation/{id}', 'archiveForm')->name('archiveForm');
    Route::get('/formations_archivees', 'archiveView')->name('archiveView');
    
    // Inscriptions
    Route::get('/Nouvelle_inscription', 'inscView')->name('inscView');
    Route::post('/Insertion_inscription', 'storeInsc')->name('storeInsc');
    Route::get('/inscriptions', 'inscriptions')->name('admin.inscriptions');
    Route::get('/Accepter_reservation/inscription={id}', 'accepterRes')->name('accepterRes');
    Route::get('/Rejeter_reservation/inscription={id}', 'rejeterRes')->name('rejeterRes');
    
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
    Route::get('/payments/search-inscriptions', 'searchInscriptions')->name('admin.payments.search');
    Route::post('/Insertion_paiement', 'storePayment')->name('storePayment');
    Route::get('/Supprimer_paiement/paiement={id}', 'supPayment')->name('supPayment');
    Route::get('/Modifier_paiement/paiement={id}', 'updatePaymentView')->name('updatePaymentView');
    Route::post('/Mise_a_jour/paiement={id}', 'updatePayment')->name('updatePayment');
    
    // Logout
    Route::post('/logout', 'logout')->name('aLogout');
});

// Route test (facultative)
Route::get('/test-mail', [MainController::class, 'testMail'])->name('testMail');


// Routes pour les Callbacks de PawaPay
Route::post('/pawapay/callback-deposit', [PawaPayController::class, 'handleDepositCallback'])->name('pawapay.callback.deposit');
Route::post('/pawapay/callback-payout', [PawaPayController::class, 'handlePayoutCallback'])->name('pawapay.callback.payout');
Route::post('/pawapay/callback-refund', [PawaPayController::class, 'handleRefundCallback'])->name('pawapay.callback.refund');

//Test route pour simuler un paiement PawaPay (à utiliser uniquement en environnement de développement)
// Route::get('/test-pawapay', function(PawaPayService $service) {
//     // 1. On simule un ID de transaction unique (UUID) obligatoire pour PawaPay
//     $depositId = (string) Str::uuid();

//     // 2. On crée d'abord le paiement "en attente" dans ta table PostgreSQL
//     // (Remplace inscription_id par un ID existant dans ta base pour éviter une erreur de clé étrangère)
//     $paiement = Paiement::create([
//         'inscription_id' => 1, 
//         'montant' => 5000,
//         'mode' => 'mobile money',
//         'reference' => $depositId, // Le depositId sert de référence unique
//         'statut' => 'en_attente',
//         'account_type' => 'principal',
//         'type_paiement' => 'pawapay',
//         'date_paiement' => now(),
//     ]);

//     // 3. On utilise un numéro de test Sandbox officiel de PawaPay
//     // Ce numéro simule un paiement instantané réussi au Congo
//     $phoneDestination = '061234567'; 

//     echo "Initialisation du paiement pour le numéro : " . $phoneDestination . "<br>";
    
//     // 4. Appel du service
//     $resultat = $service->initiateDeposit(
//         $depositId, 
//         $phoneDestination, 
//         5000, 
//         "Inscription test cours Miko"
//     );

//     if ($resultat) {
//         return "Requête acceptée par PawaPay ! En attente du Webhook... Check tes logs.";
//     } else {
//         return "Échec de communication avec l'API PawaPay. Vérifie ton TOKEN dans le .env";
//     }
// });

// La route pour afficher la salle d'attente
Route::get('/payment/awaiting/{reference}', function ($reference) {
    // return view('payment-awaiting', ['reference' => $reference]);
    return view('payment.payment-awaiting', ['reference' => $reference]);
})->name('payment.awaiting');

// La nouvelle route de succès rattachée au FirstController
Route::get('/user/payment/success/{inscriptionId}', [FirstController::class, 'showPaymentSuccess'])->name('payment.success.view');
