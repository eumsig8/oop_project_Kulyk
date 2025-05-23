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

    public function changePassword($username, $oldPassword, $newPassword) {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($oldPassword, $user['password'])) {
        return false; // wrong old password
    }

    // Decrypt old AES key using old password
    $aesKey = openssl_decrypt($user['aes_key'], AES_METHOD, $oldPassword, 0, str_repeat("0", 16));
    if ($aesKey === false) return false;

    // Re-encrypt AES key with new password
    $newEncryptedKey = openssl_encrypt($aesKey, AES_METHOD, $newPassword, 0, str_repeat("0", 16));
    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update user
    $update = $this->db->prepare("UPDATE users SET password = ?, aes_key = ? WHERE username = ?");
    return $update->execute([$newHashedPassword, $newEncryptedKey, $username]);
}

}
