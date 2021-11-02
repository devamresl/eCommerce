<?php

    session_start();
    $pageTitle = "Login";

    if (isset($_SESSION['user'])) {
        header('Location: index.php');
    }

    include "init.php";

        // Check If User Coming From HTTP Post Request

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST["login"])) {

            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashedPass = sha1($pass);
    
            // Check If the User Exist In Database
    
            $stmt = $con->prepare("SELECT
                                        UserID, Username, Password 
                                    FROM 
                                        users 
                                    WHERE 
                                        Username = ? 
                                    AND 
                                        Password = ?");
            $stmt->execute(array($user, $hashedPass));
            $get = $stmt->fetch();
            $count = $stmt->rowCount();
    
            if ($count > 0) {
                $_SESSION['user'] = $user; // Register Session Name
                $_SESSION['uid'] = $get['UserID']; // Register User ID In Session
                header('Location: index.php'); // Register To Dashboard Page
                exit();
    
            }

        } else {

            $formErrors = array();

            $username       = $_POST["username"];
            $password       = $_POST["password"];
            $password2      = $_POST["password2"];
            $email          = $_POST["email"];

            if (isset($username)) {

                $filterUser = filter_var($username, FILTER_SANITIZE_STRING);

                if (strlen($filterUser) < 4) {
                    $formErrors[] = "Username must be larger than 4 Charachters";
                }

            }

            if (isset($password) && isset($password2)) {

                if (empty($password)) {
                    $formErrors[] = "Sorry Password can't be empty";
                }

                $pass1 = sha1($password);

                $pass2 = sha1($password2);

                if ($pass1 !== $pass2) {

                    $formErrors[] = "Sorry Password is not match";

                }

            }

            if (isset($email)) {

                $filterEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

                if (filter_var($filterEmail, FILTER_SANITIZE_EMAIL) != true) {
                    $formErrors[] = "This email is not valid";
                }

            }

            // Check If There's No Error Proceed The User Add

            if (empty($formErrors)) {

                // Check if user exist in database

                $check = checkItem("Username", "users", $username);

                if ($check == 1) {

                    $formErrors[] = "Sorry This User is exist";

                } else {
                    // Insert user info in Database
                    $stmt = $con->prepare("INSERT INTO 
                                                users(Username, Password, Email, RegStatus, Date)
                                        VALUES(:zuser, :zpass, :zmail, 0, now())");
                    $stmt->execute(array(
                        
                        "zuser" => $username,
                        "zpass" => $pass1,
                        "zmail" => $email,
                    ));
                    // Echo Success Message
                    $successMsg = "Congrats You Are Now Registerd User";

                }

            }

        }
    
    }
?>

    <div class="container login-page">
        <h1 class="text-center">
            <span class="selected" data-class="login">Login</span> | 
            <span data-class="signup">Signup</span>
        </h1>
        <!-- Start Login Form -->
        <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <input 
            class="form-control" 
            type="text" 
            name="username" 
            autocomplete="off"
            placeholder="Type your username">
            <input 
            class="form-control" 
            type="password" 
            name="password" 
            autocomplete="new-password"
            placeholder="Type your password">
            <input class="btn btn-primary btn-block" name="login" type="submit" value="Login">
        </form>
        <!-- End Login Form -->
        <!-- Start Signup Form -->
        <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <input 
            pattern=".{4,}"
            title="Username Must Be 4 Charachters"
            class="form-control" 
            type="text" 
            name="username" 
            autocomplete="off"
            placeholder="Type your username"
            required>
            <input 
            minlength="4"
            class="form-control" 
            type="password" 
            name="password" 
            autocomplete="new-password"
            placeholder="Type a complex password"
            required>
            <input 
            minlength="4"
            class="form-control" 
            type="password" 
            name="password2" 
            autocomplete="new-password"
            placeholder="Type a password again"
            required>
            <input 
            class="form-control" 
            type="email" 
            name="email" 
            placeholder="Type a valid email"
            required>
            <input class="btn btn-success btn-block" name="signup" type="submit" value="Signup">
        </form>
        <!-- End Signup Form -->
        <div class="the-errors text-center">
            <?php 
                if (!empty($formErrors)) {
                    foreach ($formErrors as $error) {
                        echo '<div class="msg erroe">' . $error . '</div>';
                    }
                }

                if (isset($successMsg)) {
                    echo '<div class="msg success">' . $successMsg . '</div>';
                }
            ?>
        </div>
    </div>

<?php
    include $tpl . "footer.php";
    ob_end_flush();
?>
