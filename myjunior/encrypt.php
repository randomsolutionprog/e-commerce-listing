<?php
// URL-safe Base64 Encoding function
function base64_url_encode($input) {
    return strtr(base64_encode($input), '+/', '-_');
}

// URL-safe Base64 Decoding function
function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
}

// Encryption function
function encrypt($data, $key) {
    // Convert the number to a string before encryption
    $dataString = (string)$data;
    
    $method = 'aes-256-cbc';
    $ivLength = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($ivLength);
    $encrypted = openssl_encrypt($dataString, $method, $key, OPENSSL_RAW_DATA, $iv);
    $encrypted = base64_url_encode($iv . $encrypted);
    
    return $encrypted;
}

// Decryption function
function decrypt($data, $key) {
    $method = 'aes-256-cbc';
    $ivLength = openssl_cipher_iv_length($method);
    $data = base64_url_decode($data);
    $iv = substr($data, 0, $ivLength);
    $decrypted = openssl_decrypt(substr($data, $ivLength), $method, $key, OPENSSL_RAW_DATA, $iv);
    // Convert the decrypted string back to a number
    return (int)$decrypted;
}


$key = 'muzaffar';
?>
