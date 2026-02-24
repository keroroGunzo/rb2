<?php
require 'config/database.php';

$pass = password_hash("admin123", PASSWORD_DEFAULT);

mysqli_query($conn, "INSERT INTO users (name,email,password,role)
VALUES ('Admin','admin@toko.com','$pass','admin')");