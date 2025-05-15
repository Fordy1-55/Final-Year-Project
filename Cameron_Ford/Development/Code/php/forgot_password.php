<?php
header('Content-Type: application/json');
$email = $_POST['email'] ?? '';

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Email is required.']);
    exit();
}

// TODO: Check if email exists in your database and send a reset link.
// For now, just simulate success:
echo json_encode(['success' => true, 'message' => 'If this email is registered, a reset link has been sent.']);
?>