<?php
require '../config/init.php';
require '../services/AuthService.php';

$auth = new AuthService($db);
$auth->logout();

header("Location: login.php");
exit;