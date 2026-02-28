<?php

class AuthService
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function login($email, $password)
    {
        $user = $this->db->single(
            "SELECT * FROM users WHERE email = :email LIMIT 1",
            ['email' => $email]
        );

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // Update last login
        $this->db->execute(
            "UPDATE users SET last_login = NOW() WHERE id = :id",
            [':id' => $user['id']]
        );


        session_regenerate_id(true);

        $_SESSION['user_id']  = $user['id'];
        $_SESSION['name']     = $user['name'];
        $_SESSION['role']     = $user['role'];
        $_SESSION['store_id'] = $user['store_id'];

        return true;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
    }
}
