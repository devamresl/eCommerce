<?php

    function lang($phrase) {

        static $lang = array(

            // Navbar Links

            "HOME_ADMIN"    => "Home",
            "CATEGORIES"    => "Categories",
            "ITEMS"         => "Items",
            "MEMBERS"       => "Members",
            "COMMENTS"      => "Comments",
            "STATISTICS"    => "Statistions",
            "LOGS"          => "Logs",

        );

        return $lang[$phrase];

    }





