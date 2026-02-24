<?php
require '../config/init.php';
require '../services/AuthService.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    die("Email dan password wajib diisi.");
}

$auth = new AuthService($db);

if ($auth->login($email, $password)) {
    header("Location: ../dashboard/");
    exit;
} else {
    echo "Login gagal. Email atau password salah.";
}