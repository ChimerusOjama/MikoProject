<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reçu de paiement - Miko Formation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #212121;
            line-height: 1.6;
            font-size: 14px;
        }

        .receipt-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .receipt-header {
            background: linear-gradient(135deg, #3F51B5, #303F9F);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 12px 12px 0 0;
        }

        .logo {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .logo span {
            color: #FFCA28;
        }

        .success-icon {
            font-size: 4rem;
            margin: 20px 0;
        }

        .receipt-header h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .receipt-header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .company-info {
            text-align: right;
            padding: 20px;
            font-size: 13px;
            color: #757575;
            border-bottom: 1px solid #e0e0e0;
        }

        .client-info {
            background: #F5F5F5;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3F51B5;
        }

        .client-info h3 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: #3F51B5;
            margin-bottom: 15px;
        }

        .client-info p {
            margin: 5px 0;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .details-table th {
            background: #3F51B5;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 500;
        }

        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        .details-table tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            background: #F5F5F5;
            font-weight: 700;
        }

        .total-row td {
            font-size: 1.1em;
        }

        .payment-method {
            background: #F5F5F5;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #FFCA28;
        }

        .payment-method p {
            margin: 5px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            background: rgba(76, 175, 80, 0.15);
            color: #4CAF50;
            border-radius: 4px;
            font-weight: 600;
            font-size: 12px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px dashed #e0e0e0;
            text-align: center;
            font-size: 12px;
            color: #757575;
        }

        .footer p {
            margin: 5px 0;
        }

        @media print {
            body {
                background: white;
            }
            .receipt-container {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- <div class="receipt-header">
            <div class="logo">MIKO<span>FORMATION</span></div>
            <div class="success-icon">✅</div>
            <h1>Reçu de paiement</h1>
            <p>Merci pour votre confiance</p>
        </div> -->

        <div class="company-info">
            <strong>Miko Formation</strong><br>
            Congo Brazzaville<br>
            contact@mikoformation.cg<br>
            +242 XX XX XX XX
        </div>

        <div class="client-info">
            <h3>Client</h3>
            <p><strong>Nom :</strong> {{ $inscription->name }}</p>
            <p><strong>Email :</strong> {{ $inscription->email }}</p>
            <p><strong>Téléphone :</strong> {{ $inscription->phone }}</p>
            <p><strong>Adresse :</strong> {{ $inscription->address }}</p>
        </div>

        <h3 style="margin-bottom: 10px; font-family: 'Poppins', sans-serif;">Détails de la transaction</h3>
        <table class="details-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $inscription->formation->titre }}</td>
                    <td>1</td>
                    <td>{{ number_format($inscription->montant, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($inscription->montant, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;"><strong>Total payé :</strong></td>
                    <td><strong>{{ number_format($inscription->montant, 0, ',', ' ') }} FCFA</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="payment-method">
            <p><strong>Méthode de paiement :</strong> Carte bancaire (Stripe)</p>
            <p><strong>Référence :</strong> {{ $inscription->stripe_session_id }}</p>
            <p><strong>Date :</strong> {{ $inscription->payment_date ?? now()->format('d/m/Y H:i') }}</p>
            <p><strong>Statut :</strong> <span class="status-badge">Payé</span></p>
            <p><strong>Accès à la formation :</strong> du {{ now()->format('d/m/Y') }} au {{ now()->addMonths(3)->format('d/m/Y') }}</p>
        </div>

        <div class="footer">
            <p>Ce reçu fait office de justificatif de paiement.</p>
            <p>Miko Formation – Connaissance, Compétence, Excellence</p>
        </div>
    </div>
</body>
</html>