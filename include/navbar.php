<?php

use App\Pdo\Database;

if (isset($_SESSION['userId'])) {
    $queryUserImage = 'SELECT users.image FROM users WHERE users.id=?';
    $conn = new Database();
    $userImage = $conn->dbQuery($queryUserImage, [$_SESSION['userId']]);
    $resultUserImage = implode(" ", $userImage[0]);
    $resultUserImage = substr($resultUserImage, 24);
}
?>

<header class="fixed-top">
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">

            <a class="navbar-brand" href="/<?= basename(dirname(__FILE__, 2)) ?>">
                <i class="bi-people-fill pe-1"></i>
                My Blog
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="all-posts.php">Blog</a>
                    </li>
                </ul>


                <div class="d-flex">
                    <ul class="navbar-nav">
                        <?php if (isset($_SESSION['userId'])) { ?>
                            <li class="nav-item me-5 d-flex">
                                <img class="avatar" src="<?= $resultUserImage ?>" alt="user avatar">
                                <div class="nav-link active" aria-current="page" href="all-posts.php">Hi <?= $_SESSION['userName'] ?> </div>
                            </li>
                        <?php } ?>


                        <?php if (!isset($_SESSION['userId'])) { ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="signup.php">Sign Up</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="login.php">Login</a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="logout.php">Logout</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>