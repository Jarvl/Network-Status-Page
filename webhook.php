<?php
require_once("init.php");

$secret = $GLOBALS['config']['misc']['githubWebhookSecret'];
$headers = getallheaders();
$hubSignature = $headers['X-Hub-Signature'];

// Split signature into algorithm and hash
list($algo, $hash) = explode('=', $hubSignature, 2);

// Get payload
$payload = file_get_contents('php://input');

// Calculate hash based on payload and the secret
$payloadHash = hash_hmac($algo, $payload, $secret);

// Check if hashes are equivalent
if ($hash !== $payloadHash) {
    echo 'Bad secret';
}
else {
    $data = json_decode($payload);
    exec("cd /var/www/Network-Status-Page && git pull");
    echo "thanks";
}
