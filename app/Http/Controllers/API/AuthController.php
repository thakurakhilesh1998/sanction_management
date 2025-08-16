<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Support\Facades\Hash;
use App\Helpers\EncryptionHelper;

class AuthController extends Controller
{
    public function login(Request $request)
    {
       
        $key="0vXqvr7q9JMMsF4kvnlSTbZ8StibB+MU";
        // $plaintext = '{"username":"xen_mandi","password":"Admin@123"}';
        // $etest=EncryptionHelper::encrypt($plaintext,$key);
        // echo "encrypted test:". $etest;
        // //  $dtest=EncryptionHelper::decrypt($etest,$key);
        // //  echo $dtest;

        try {
            $decryptedData = EncryptionHelper::decrypt(file_get_contents("php://input"),$key);
            $data = json_decode($decryptedData, true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        $credentials = [
            'username' => $data['username'] ?? null,
            'password' => $data['password'] ?? null, 
        ];
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Generate a token (if you are using Laravel Sanctum or Passport)
            $token = $user->createToken('authToken')->plainTextToken;
            $response = [
                'message' => 'Login Successful',
                'token' => $token,
                'role' => $user->role,
                'zone' => $user->zone,
            ];
            $encryptedResponse = EncryptionHelper::encrypt(json_encode($response),$key);
            return response($encryptedResponse, 200);
        } else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }



    }
}