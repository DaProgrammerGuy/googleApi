<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Google_Client;
use Illuminate\Http\Request;

class googleAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function login(Request $request){

        $idToken = $request->id_token;

        if (!$idToken){
            return response()->json(['error' => 'No token provided'], 400);
        }

        $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);

        try {
        $payload = $client->verifyIdToken($idToken);
        } catch (Exception $e) {
            return response()->json(['error' => 'Invalid Token'], 401);
        } 

        if(!$payload){
            return response()->json(['error' => 'Invalid Token'], 401);
        }

        $googleId = $payload['sub'];
        $email = $payload['email'];
        // $name = $payload['name'];
        $name = $payload['name'] ?? 'Google User';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'google_id' => $googleId,
                'password' => bcrypt(random_bytes(16)),
                'email_verified_at' => now()
            ]
            );

            $token = $user->createToken('app')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
    }

    
}
