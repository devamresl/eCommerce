<?php

    ob_start(); // Output Buffering Start

    session_start();

    if (isset($_SESSION['Username'])) {

        $pageTitle = "Dashboard";

        include "init.php";

        /* Start Dashboard Page */
        $numUsers = 5; // Number of latest users
        $latestUsers = getLatest("*", "users", "UserID", $numUsers); // Latest users array

        $numItems = 6; // Number of latest Items
        $latestItems = getLatest("*", "items", "item_ID", $numItems); // Latest Items array

        $numComments = 4; // Number of Comments

        ?>

        <div class="container home-stats text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                        Total Members
                        <span><a href="members.php"><?php echo countItems("UserID", "users") ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending Members
                            <span><a href="members.php?do=Manage&page=Pending">
                                <?php echo checkItem("RegStatus", "users", 0); ?>
                            </a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                            <span><a href="items.php"><?php echo countItems("item_ID", "items") ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            Total Comments
                            <span><a href="comments.php"><?php echo countItems("c_id", "comments") ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container latest">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i> Latest <?php echo $numUsers; ?> Registerd Users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                <?php
                        if (!empty($latestUsers)) {
                        foreach ($latestUsers as $user) {
                            echo "<li>" . $user["Username"] . 
                            " <span class='btn btn-success pull-right'> 
                            <i class='fa fa-edit'></i> <a href='members.php?do=Edit&userid=" . $user["UserID"] . "'>Edit</a></span>" . 
                            "</li>";
                        }
                    } else {
                        echo "There's No Msmber To Show";
                    }
                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> Latest <?php echo $numItems; ?> Items
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-items">
                <?php
                        if (!empty($latestItems)) {
                        foreach ($latestItems as $item) {
                            echo "<li>" . $item["Name"] . 
                            " <span class='btn btn-success pull-right'> 
                            <i class='fa fa-edit'></i> <a href='items.php?do=Edit&itemid=" . $item["item_ID"] . "'>Edit</a></span>" . 
                            "</li>";
                        }
                    } else {
                        echo "There's No Items To Show";
                    }
                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- Start Latest Comments -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comments"></i> 
                            Latest <?php echo $numComments; ?> Comments
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                        <?php 

                            // Select all users except page admin
                            $stmt = $con->prepare("SELECT 
                                                    comments.*, users.Username AS Member
                                                FROM 
                                                    comments
                                                INNER JOIN
                                                    users
                                                ON
                                                    users.UserID = comments.user_id
                                                ORDER BY c_id DESC
                                                LIMIT $numComments");
                        // Execute the statement
                        $stmt->execute();
                        // Assign to variable
                        $comments = $stmt->fetchAll();

                        if (!empty($comments)) {

                        foreach ($comments as $comment) {
                            echo '<div class="comment-box">';
                                echo '<span class="member-n">' .  $comment['Member'] . '</span>';
                                echo '<p class="member-c">' .  $comment['comment'] . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo "There's No Comments To Show";
                    }
                            
                    ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Latest Comments -->
        </div>

        <?php
        /* End Dashboard Page */

    } else {

        header('Location: index.php');

        exit();

    }

    ob_end_flush();

?>
