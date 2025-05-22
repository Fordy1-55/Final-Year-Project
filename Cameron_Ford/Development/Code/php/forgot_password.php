<?php
header('Content-Type: application/json');
$email = $_POST['email'] ?? '';

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Email is required.']);
    exit();
}


echo json_encode(['success' => true, 'message' => 'If this email is registered, a reset link has been sent.']);
?>