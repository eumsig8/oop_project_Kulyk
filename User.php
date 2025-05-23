<?php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function register($username, $password) {
        // Check if username already exists
        $check = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$username]);
        if ($check->fetch()) return false;

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $aesKey = bin2hex(random_bytes(16)); // Generate 128-bit key
        $encryptedKey = openssl_encrypt($aesKey, AES_METHOD, $password, 0, str_repeat("0", 16));

        $stmt = $this->db->prepare("INSERT INTO users (username, password, aes_key) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $hashedPassword, $encryptedKey]);
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
}
