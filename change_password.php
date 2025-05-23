<?php
require_once "config.php";
require_once "classes/Database.php";
require_once "classes/User.php";

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['user']['username'];
    $oldPass = $_POST['old_password'];
    $newPass = $_POST['new_password'];

    if ($user->changePassword($username, $oldPass, $newPass)) {
        $message = "✅ Password changed successfully.";
    }
    } else {
        $message = "❌ Failed to change password. Check your old password.";
    }
}
?>

<h2>Change Password</h2>
<p><?= $message ?></p>
<form method="post">
    Old Password: <input type="password" name="old_password" required><br><br>
    New Password: <input type="password" name="new_password" required><br><br>
    <button type="submit">Change Password</button>
</form>
<p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
