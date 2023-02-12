<?php
require_once './config/config.php';
require_once './providers/database.php';

use App\Pdo\Database;

session_start();

if (!isset($_SESSION['userId'])) {
    header('location: login.php');
}

$_SESSION['token'] = sha1('Aa$124$!re');
$error = '';

if (isset($_POST['submit']) && !empty($_SESSION['token'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW);
    $body = filter_input(INPUT_POST, 'body', FILTER_UNSAFE_RAW);

    try {
        if (empty($title) || empty($body)) {
            throw new Exception('All fields are required');
        }

        $conn = new Database();
        $result = $conn->dbQuery(
            "INSERT INTO posts(`id`,`title`,`user_id`,`last_update`,`body`,`image_url`,`image_alt`) VALUES(NULL,?,?,CURRENT_TIMESTAMP,?,NULL,NULL)",
            [$title, $_SESSION['userId'], $body]
        );

        header('location:all-posts.php');
    } catch (Exception $err) {
        $error = $err->getMessage();
    }
}

include './include/header.php';
?>

<h2 class="my-3">Add New Post</h2>

<form action="add-post.php" method="post" accept-charset="UTF-8" class="p-4 form text-start">
    <div class="mb-3">
        <label for="title" class="form-label">Post Title</label>
        <input type="text" name="title" id="title" class="form-control">
    </div>
    <div class="mb-3">
        <label for="body" class="form-label">Type your text</label>
        <textarea name="body" id="body" cols="30" rows="10" class="form-control"></textarea>
    </div>

    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

    <input type="submit" value="Add" name="submit" class="btn btn-primary btn-lg">
    <a href="profile.php" class="btn btn-secondary btn-lg">Cancel</a>
</form>

<p class="text-danger">
    <?= $error ?>
</p>


<?php
include './include/footer.php';
