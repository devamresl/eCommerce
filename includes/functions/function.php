<?php

    // Get All Function v2.0

    function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderField, $ordering = "DESC") {

        global $con;

        $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $ordering");

        $getAll->execute();

        $all = $getAll->fetchAll();

        return $all;

    }

    // Get Items Function

    function getItems($where, $value, $approve = NULL) {

        global $con;

        $sql = $approve == NULL ? "AND Approve = 1" : "";

        $getItems = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY item_ID DESC");

        $getItems->execute(array($value));

        $items = $getItems->fetchAll();

        return $items;

    }

    // Check If User Is Not Activated
    // Function to check the regstatus of the user

    function checkUserStatus($user) {

        global $con;
    
        $stmtx = $con->prepare("SELECT
                                    Username, RegStatus 
                                FROM 
                                    users 
                                WHERE 
                                    Username = ? 
                                AND 
                                    RegStatus = 0");
        $stmtx->execute(array($user));
        $status = $stmtx->rowCount();

        return $status;
    }

    function checkItem($select, $from, $value) {

        global $con;

        $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

        $statement->execute(array($value));

        $count = $statement->rowCount();

        return $count;

    }






























    function getTitle() {

        global $pageTitle;
        
        if (isset($pageTitle)) {
            echo $pageTitle;
        } else {
            echo "Default";
        }

    }

    function redirectHome($theMsg, $url = null, $seconds = 3) {

        if ($url === null) {
            $url = "index.php";
            $link = "Homepage";
            
        } else {
            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== "") {

                $url = $_SERVER['HTTP_REFERER'];
                $link = "Previous Page";
            } else {
                $url = "index.php";
                $link = "Homepage";
            }
            
        }

        echo $theMsg;

        echo "<div class='alert alert-info'>You will be redirected to $link after $seconds seconds.</div>";

        header("refresh:$seconds;url=$url");

        exit();

    }
/*
    function checkItem($select, $from, $value) {

        global $con;

        $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

        $statement->execute(array($value));

        $count = $statement->rowCount();

        return $count;

    }*/
    
    function countItems($item, $table) {

        global $con;

        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

        $stmt2->execute();

        return $stmt2->fetchColumn();

    }

    function getLatest($select, $table, $order, $limit) {

        global $con;

        $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

        $getStmt->execute();

        $rows = $getStmt->fetchAll();

        return $rows;

    }



