<?php

    // Error Reporting

    ini_set("display_error", "On");
    error_reporting(E_ALL);

    include "admin/connect.php";

    $sessionUser = "";

    if (isset($_SESSION["user"])) {
        $sessionUser = $_SESSION["user"];
    }

    $tpl        = "includes/templates/"; // Template Directory
    $lang       = "includes/languages/"; // Language Directory
    $func       = "includes/functions/"; // Function Directory
    $css        = "layout/css/"; // Css Directory
    $js         = "layout/js/"; // Js Directory
    

    // Include The Impotant Files

    include $func . "function.php";
    include $lang . "english.php";
    include $tpl . "header.php";
    