<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php  getTitle() ?></title>
        <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">
        <link crossorigin='anonymous' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' rel='stylesheet'/>
        <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css">
        <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css">
        <link rel="stylesheet" href="<?php echo $css; ?>front.css">
    </head>
    <body>
        <div class="upper-bar">
            <div class="container">

                <?php
                    if (isset($_SESSION['user'])) { ?>

                    <?php
                        echo "Welcome " . $sessionUser . " ";
                        echo '<a href="profile.php">My Profile</a>';
                        echo ' - <a href="newadd.php">New Item</a>';
                        echo ' - <a href="logout.php">Logout</a>';
                        $userStatus = checkUserStatus($sessionUser);
                        if ($userStatus == 1) {
                            // User Is Not Activ
                        }
                    } else {
                ?>

                <a href="login.php">
                    <span class="pull-right">Login/Signup</span>
                </a>
                <?php } ?>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-dark  bg-dark">
        <div class="container">
        <a class="navbar-brand" href="index.php">Home Page</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-app" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav-app">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 navbar-right">
            <?php
                $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "ASC");
                foreach ($allCats as $cat) {
                    echo 
                    '<li>
                        <a href="categories.php?pageid=' . $cat["ID"] . '">
                            ' . $cat["Name"] . '
                        </a>
                    </li>';
                        }
            ?>
            </ul>
        </div>
        </div>
    </nav>
