<?php

require_once './config/config.php';
require_once './providers/database.php';

use App\Pdo\Database;

session_start();
$_SESSION['token'] = sha1('Aa$124$!re');

$error = '';

if (isset($_POST['submit']) && !empty($_SESSION['token'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

    try {
        if (empty($email) || empty($password)) {
            throw new Exception("All fields are required");
        }

        $conn = new Database();
        $result = $conn->dbQuery(
            "SELECT * FROM users WHERE email=?",
            [$email]
        );

        if (count($result) === 0) {
            throw new Exception("Failed to login, check email or password");
        }

        $user = $result[0];

        if (!password_verify($password, $user['password'])) {
            throw new Exception("Failed to login, check password");
        }

        $_SESSION['userId'] = $user['id'];
        $_SESSION['userName'] = $user['name'];

        header('location: all-posts.php');
    } catch (Exception $err) {
        $error = $err->getMessage();
    }
}

include './include/header.php';
?>


<h2 class="my-3">Login</h2>

<form action="login.php" method="post" accept-charset="UTF-8" class="p-4 form text-start">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="your@email.com">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>
    <div>

    </div>
    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

    <input type="submit" value="Login" name="submit" class="btn btn-primary btn-lg">
</form>

<p class="text-danger">
    <?= $error ?>
</p>



<?php
include './include/footer.php';
