<?php
require_once "User.php";
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    if ($user->register($_POST['username'], $_POST['password'])) {
        echo "User registered successfully!";
    } else {
        echo "Registration failed.";
    }
}
?>

<form method="post">
    <input type="text" name="username" placeholder="Username" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Register</button>
</form>
