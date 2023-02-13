<?php

require_once './config/config.php';
require_once './providers/database.php';

use App\Pdo\Database;

session_start();
$_SESSION['token'] = sha1('Aa$124$!re');
$error = '';

$conn = new Database();
$queryChckEmail = 'SELECT users.email FROM users';
$resultCheckEmail = $conn->dbQuery($queryChckEmail, []);


if (isset($_POST['submit']) && !empty($_SESSION['token'])) {

    $name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW);
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    try {
        if (empty($name) || empty($password) || empty($email)) {
            throw new Exception('name, password and email are required');
        }

        $emailRegex = "/^[\w-]+(\.[\w-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";
        if (!$email || !preg_match($emailRegex, $email)) {
            throw new Exception('Invalid email address');
        }

        foreach ($resultCheckEmail as $checkEmail) {
            if ($checkEmail['email'] === $email) {
                throw new Exception('User is already exists, try with enother email');
            }
        }

        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            throw new Exception('Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.');
        }


        $image_field = $_FILES['image'];

        if (empty($image_field) || $image_field['error'] != UPLOAD_ERR_OK) {
            throw new Exception("Error uploading file. {$_FILES['image']['error']}");
        }

        if (is_uploaded_file($image_field['tmp_name'])) {

            if ($image_field['size'] > UPLOAD_MAX_SIZE) {
                throw new Exception("File is too large.");
            }

            $file_info = pathinfo($image_field['name']);
            $file_ext = strtolower($file_info['extension']);

            if (!in_array($file_ext, FILE_EXT)) {
                throw new Exception("Only files of type: 'jpg', 'jpeg', 'png', 'gif', 'webp' are allowed");
            }

            $upload_file = UPLOAD_DIR . date('Y.m.d.H.i.s') . '-' . basename($image_field['name']);

            if (move_uploaded_file($image_field['tmp_name'], $upload_file)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $conn->dbQuery(
                    "INSERT INTO users (`id`, `name`, `email`, `password`,`image`) VALUES (NULL,?,?,?,?)",
                    [$name, $email, $hash, $upload_file]
                );

                if ($conn->affectedCount() === 0) {
                    throw new Exception('Error. Check your fileds input...');
                }
                //Its better safe move the user to logi page then move him to the posts page...
                header('location:login.php');
            } else {
                throw new Exception("Upload failed. Check permission and path of upload folder.");
            }
        }
    } catch (Exception $err) {
        $error = $err->getMessage();
    }
}

include './include/header.php';
?>

<h2 class="my-3 ">Sign Up</h2>
<h2 class="my-3 mt-5">Sign Up</h2>

<form action="signup.php" method="post" enctype="multipart/form-data" class="p-4 form text-start">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" id="name" class="form-control">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" name="email" id="email" class="form-control" placeholder="your@email.com">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Profile Image</label>
        <input type="file" name="image" id="image" class="form-control">
    </div>

    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

    <input type="submit" value="Sign Up" name="submit" class="btn btn-primary btn-lg">
</form>

<p class="text-danger">
    <?= $error ?>
</p>


<?php
include './include/footer.php';
