<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MainController extends Controller
{
    //
    public function testMail() {
        $apiKey = env('BREVO_API_KEY');
        $senderEmail = env('BREVO_SENDER_EMAIL');
        $senderName = env('BREVO_SENDER_NAME');

        $response = Http::withHeaders([
            'api-key' => $apiKey,
            'content-type' => "application/json",
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => $senderName, // Ou "Miko Formation"
                'email' => $senderEmail,
            ],
            'to' => [
                [
                    'email' => 'berchebaisrael@gmail.com',
                    'name' => auth()->user()->name ?? 'Utilisateur',
                ],
            ],
            'subject' => 'Test Email',
            'htmlContent' => '<html><body><h1>Hello, this is a test email!</h1></body></html>',
        ]);

        if ($response->successful()) {
            return response()->json(['message' => 'Email sent successfully!']);
        } else {
            return response()->json([
                'message' => 'Failed to send email.',
                'errors' => $response->json()
            ], $response->status());
        }
        // Uncomment the following lines if you want to use the Mail facade instead of HTTP client 






        // $apikey = config('app.api_key');
        // if (!$apikey) {
        //     return response()->json(['message' => 'API key not set in .env file'], 500);
        // }

        // $user = User::find(1);
        // if ($user) {
        //     \Mail::to($user->email)->send(new \App\Mail\TestMail($user));
        //     return response()->json(['message' => 'Email sent successfully!']);
        // }
        // return response()->json(['message' => 'User not found!'], 404);
    }
}
