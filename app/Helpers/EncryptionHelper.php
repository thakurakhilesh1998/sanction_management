<?php
    namespace App\Helpers;
    use Exception;
class EncryptionHelper
{
    private static $key = "0vXqvr7q9JMMsF4kvnlSTbZ8StibB+MU"; // 32-byte key for AES-256

    public static function encrypt($plainText, $key)
    {
        // Ensure the key is 32 bytes (AES-256 requires it)
        $key = substr(hash('sha256', $key, true), 0, 32);

        // Generate a random IV (16 bytes for AES)
        $iv = openssl_random_pseudo_bytes(16);

        // Encrypt the plaintext
        $encryptedText = openssl_encrypt($plainText, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        // Combine the IV and the encrypted text
        $encrypted = base64_encode($iv . $encryptedText);

        return $encrypted;
    }

    public static function decrypt(string $encryptedText, string $key): string
    {
        $keyBytes = substr(hash('sha256', $key, true), 0, 32);
        // $encryptedText="s/T2ZRIM98ZFWDNilU0l1//kL3Vk1+f7W7Bk2+f4EdyZQZXb1CaIfFA+T2vgvNO49oRLztNH+R4lurlRQofQQjZrAsDGID7jSOTZCTNwvISpIbUlRe38cw3BILKuyE9KwFIxR2MRGu3vYcYNEkLl6bx3ir2jrH9Kap1T/HfLUX2VwhY3w85NYB6kQgbPSiJX";
        $encryptedBytes = base64_decode(str_replace(["\r", "\n"], '', $encryptedText));
        if ($encryptedBytes === false) {
            throw new Exception('Invalid Base64 string.');
        }
    
        $iv = substr($encryptedBytes, 0, 16);
        $cipherText = substr($encryptedBytes, 16);
    
        if (strlen($iv) !== 16) {
            throw new Exception('IV length mismatch. Expected 16 bytes, got ' . strlen($iv));
        } 
        $decryptedText = openssl_decrypt($cipherText, 'aes-256-cbc', $keyBytes, OPENSSL_RAW_DATA, $iv);
        if ($decryptedText === false) {
            throw new Exception('Decryption failed.');
        }
    
        return $decryptedText;
    }
}