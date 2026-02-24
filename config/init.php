<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

require __DIR__ . '/Database.php';

$db = new Database("localhost", "rizky_berkah", "root", "Deeden23");