<?php
require_once "config.php";
require_once "classes/Database.php";
require_once "classes/User.php";

session_start();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->login($username, $password)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Invalid username or password.";
    }
}
?>

<h2>Login</h2>
<p><?= $message ?></p>
<form method="post">
    <input type="text" name="username" placeholder="Username" required /><br><br>
    <input type="password" name="password" placeholder="Password" required /><br><br>
    <button type="submit">Login</button>
</form>
