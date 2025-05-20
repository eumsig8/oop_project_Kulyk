<?php
require_once "Database.php";

class User {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function register($username, $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $key = openssl_encrypt($this->generateKey(), AES_METHOD, $password, 0, str_repeat('0', 16));

        $stmt = $this->db->prepare("INSERT INTO users (username, password, aes_key) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $hashed, $key]);
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            return true;
        }
        return false;
    }

    private function generateKey() {
        return bin2hex(random_bytes(16));
    }
}
?>
