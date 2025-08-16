<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules= [
           'username' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'password' => [
                'required', 
                'confirmed', 
                'min:8', 
                'regex:/[a-z]/',      // at least one lowercase letter
                'regex:/[A-Z]/',      // at least one uppercase letter
                'regex:/[0-9]/',      // at least one digit
                'regex:/[@$!%*?&#]/'  // at least one special character
            ], 
            'iv_p' => ['required', 'string'],
            'tag_p' => ['required', 'string'],
            'iv_cnf' => ['required', 'string'],
            'tag_cnf' => ['required', 'string'],
        ];
        return $rules;
    }

     /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Decrypt password and password confirmation
        $decryptedP = $this->decryptPassword($this->input('password'), $this->input('iv_p'), $this->input('tag_p'));
        $decryptedC = $this->decryptPassword($this->input('password_confirmation'), $this->input('iv_cnf'), $this->input('tag_cnf'));
        
        // Set the decrypted passwords back to the request data
        $this->merge([
            'password' => $decryptedP,
            'password_confirmation' => $decryptedC,
        ]);
    }

        /**
     * Custom decryption function for password and password confirmation.
     */
    private function decryptPassword($encryptedPassword, $iv, $tag)
    {
        // Convert the base64-encoded strings back to binary data
        $ciphertext = base64_decode($encryptedPassword);
        $iv = base64_decode($iv);
        $tag = base64_decode($tag);

        // Ensure the IV is 12 bytes long
        if (strlen($iv) !== 12) {
            throw new \Exception("The IV must be 12 bytes long for AES-GCM. IV length: " . strlen($iv));
        }

        // Check if the tag length is 16 bytes (recommended for AES-GCM)
        if (strlen($tag) !== 16) {
            throw new \Exception("The authentication tag must be 16 bytes long for AES-GCM. Tag length: " . strlen($tag));
        }

        $key = '0d78c5f79ece7388c918eac45a7aad89'; // Your AES key
        $cipher = 'aes-256-gcm';

        // OpenSSL decryption (AES-GCM)
        return openssl_decrypt($ciphertext, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);
    }
}
