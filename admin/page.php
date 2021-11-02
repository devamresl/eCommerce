<?php

    $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";


    if ($do == "Manage") {

        echo "Welcome You Are In Manage Category Page ";
        echo "<a href='?do=Insert'>Add New Category +</a>";

    } elseif ($do == "Add") {

        echo "Welcome You Are In Add Category Page";
        
    } elseif ($do == "Insert") {

        echo "Welcome You Are In Insert Category Page";

    } else {

        echo "Error There's No Page With This Name";

    }









    ob_start();

    session_start();

    $pageTitle = "Itmes";

    if (isset($_SESSION["Username"])) {

        $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";

        include "init.php";

        echo "Welcome" . "<br>";


        if ($do == "Manage") {

            echo "Welcome You Are In Manage Category Page ";
            echo "<a href='?do=Insert'>Add New Category +</a>";

        } elseif ($do == "Add") {

            echo "Welcome You Are In Add Category Page";
            
        } elseif ($do == "Insert") {

            echo "Welcome You Are In Insert Category Page";

        } elseif ($do == "Edit") {

        } elseif ($do == "Update") {

        } elseif ($do == "Delete") {

        } elseif ($do == "Activate") {

        } 

        include $tpl . "footer.php";
        
        else {

            echo "Error There's No Page With This Name";

        }

    }

    ob_end_flush();


