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

    if ($user->register($username, $password)) {
        $message = "Registration successful. You can now <a href='login.php'>login</a>.";
    } else {
        $message = "Username already exists or registration failed.";
    }
}
?>

<h2>Register</h2>
<p><?= $message ?></p>
<form method="post">
    <input type="text" name="username" placeholder="Username" required /><br><br>
    <input type="password" name="password" placeholder="Password" required /><br><br>
    <button type="submit">Register</button>
</form>
