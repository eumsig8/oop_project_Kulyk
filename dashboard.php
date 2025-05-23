<?php
require_once "config.php";
require_once "classes/Database.php";
require_once "classes/PasswordManager.php";
require_once "classes/PasswordGenerator.php";

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$passwordManager = new PasswordManager($user);
$saveMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_password'])) {
    $userPassword = $_POST['login_password'];
    $service = $_POST['service'];
    $pass = $_POST['password'];

    if ($passwordManager->savePassword($service, $pass, $userPassword)) {
        $saveMessage = "Password saved.";
    } else {
        $saveMessage = "Failed to save password.";
    }
}

$generatedPassword = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_password'])) {
    $length = $_POST['length'];
    $upper = $_POST['upper'];
    $lower = $_POST['lower'];
    $nums = $_POST['numbers'];
    $special = $_POST['special'];

    $generatedPassword = PasswordGenerator::generate($length, $upper, $lower, $nums, $special);
}

$savedPasswords = $passwordManager->listPasswords($_POST['login_password'] ?? '');
?>

<h2>Welcome, <?= htmlspecialchars($user['username']) ?> | <a href="logout.php">Logout</a></h2>

<hr>

<h3>Generate Password</h3>
<form method="post">
    Length: <input type="number" name="length" value="10" required><br>
    Uppercase: <input type="number" name="upper" value="2" required><br>
    Lowercase: <input type="number" name="lower" value="2" required><br>
    Numbers: <input type="number" name="numbers" value="2" required><br>
    Special: <input type="number" name="special" value="2" required><br>
    <button type="submit" name="generate_password">Generate</button>
</form>

<?php if ($generatedPassword): ?>
    <p><strong>Generated:</strong> <?= $generatedPassword ?></p>
<?php endif; ?>

<h2>You can change your password here, <?= htmlspecialchars($user['username']) ?>
    <a href="change_password.php">Change Password</a>
</h2>
