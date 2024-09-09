<?php

use function 'pesapal.php';
require_once '../vendor/autoload.php';

// Obtain the token
$token = Pesapal::pesapalAuth();
if (!$token->success) {
    jsonResponse(['success' => false, 'message' => 'Token retrieval failed']);
}

// Register IPN
$url = "https://87ca-102-70-10-227.ngrok-free.app";
$registerIPN = Pesapal::pesapalRegisterIPN($url);
if (!$registerIPN->success) {
    jsonResponse(['success' => false, 'message' => 'IPN registration failed', 'response' => $registerIPN]);
}

// Proceed to initiate payment process
$amount = 2;
$phone = '';  // Fill this with valid phone number
$validation_callback = '';  // Fill this with a valid callback URL
$ipnId = $registerIPN->message->ipn_id ?? '';  // Use the registered IPN ID

if (empty($ipnId)) {
    jsonResponse(['success' => false, 'message' => 'IPN ID missing from registration response']);
}

$payRequest = Pesapal::orderProcess($amount, $phone, $validation_callback, $ipnId);
if (!$payRequest->success) {
    jsonResponse(['success' => false, 'message' => 'Payment processing failed', 'response' => $payRequest]);
}

// Validate Payment if needed
$transactionStatus = Pesapal::transactionStatus();
jsonResponse($transactionStatus);
