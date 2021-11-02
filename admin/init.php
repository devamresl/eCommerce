<?php

    include "connect.php";

    $tpl    = "includes/templates/"; // Template Directory
    $lang   = "includes/languages/"; // Language Directory
    $func    = "includes/functions/"; // Function Directory
    $css    = "layout/css/"; // Css Directory
    $js     = "layout/js/"; // Js Directory
    

    // Include The Impotant Files

    include $func . "function.php";
    include $lang . "english.php";
    include $tpl . "header.php";
    // include $tpl . "navbar.php";

    // Include Navbar On All Pages Expect The One With $noNavbar Variable

    if (!isset($noNavbar)) { include $tpl . "navbar.php"; }
    
    