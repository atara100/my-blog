<?php
require_once './config/config.php';
require_once './providers/database.php';
require_once './providers/utils.php';

use App\Pdo\Database;

session_start();

if (!isset($_SESSION['userId'])) {
    header('location: login.php');
}

$error = '';
$post_id = $_GET['id'];
$posts = [];

try {
    $posts = post_by_id($post_id);
} catch (Exception $err) {
    $error = $err->getMessage();
}

$_SESSION['token'] = sha1('Aa$124$!re');

if (isset($_POST['submit']) && !empty($_SESSION['token'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW);
    $body  = filter_input(INPUT_POST, 'body', FILTER_UNSAFE_RAW);

    try {
        if (empty($title) || empty($body)) {
            throw new Exception("All fields are required");
        }

        $pid = $_POST['post_id'];
        $datetime = date('Y-m-d H:i:s');
        $conn = new Database();
        $result = $conn->dbQuery(
            "UPDATE `posts` SET `title`=?, `last_update`=?, `body`=? WHERE id=?",
            [$title, $datetime, $body, $pid]
        );

        header('location: all-posts.php');
    } catch (Exception $err) {
        $error = $err->getMessage();
    }
}

include './include/header.php';
?>

<h2 class="my-3">Update Your Post</h2>

<?php
if (count($posts) > 0) {
?>

    <form action="update-post.php" method="post" accept-charset="UTF-8" class="p-4 form text-start">
        <div class="mb-3">
            <label for="title" class="form-label">Post Title</label>
            <input type="text" name="title" id="title" value="<?= $posts['title'] ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="body" class="form-label">Type your text</label>
            <textarea name="body" id="body" cols="30" rows="10" class="form-control"><?= $posts['body'] ?></textarea>
        </div>

        <input type="hidden" name="post_id" value="<?= $post_id ?>">
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

        <input type="submit" value="Update" name="submit" class="btn btn-primary btn-lg">
        <a href="all-posts.php" class="btn btn-secondary btn-lg">Cancel</a>
    </form>

<?php } ?>

<p class="text-danger">
    <?= $error ?>
</p>



<?php
include './include/footer.php';
