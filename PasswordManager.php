<?php
require_once "Database.php";

class PasswordManager {
    private $db;
    private $user;

    public function __construct($user) {
        $this->db = Database::connect();
        $this->user = $user;
    }

    public function savePassword($name, $plainPassword, $userPassword) {
        $aesKey = openssl_decrypt($this->user['aes_key'], AES_METHOD, $userPassword, 0, str_repeat('0', 16));
        $encrypted = openssl_encrypt($plainPassword, AES_METHOD, $aesKey, 0, str_repeat('0', 16));

        $stmt = $this->db->prepare("INSERT INTO passwords (user_id, name, password, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$this->user['id'], $name, $encrypted]);
    }

    public function listPasswords($userPassword) {
        $aesKey = openssl_decrypt($this->user['aes_key'], AES_METHOD, $userPassword, 0, str_repeat('0', 16));

        $stmt = $this->db->prepare("SELECT * FROM passwords WHERE user_id = ?");
        $stmt->execute([$this->user['id']]);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['password'] = openssl_decrypt($row['password'], AES_METHOD, $aesKey, 0, str_repeat('0', 16));
            $results[] = $row;
        }
        return $results;
    }
}
?>
