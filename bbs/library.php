<?php
/* escape special chars */
    function h($value){
            return htmlspecialchars($value, ENT_QUOTES);
    }

/* connect db */
    function dbconnect(){
        $db = new mysqli("localhost:8889", "root", "root", "min_bbs");
        if(!$db){
            die($db -> error);
        }
        return $db;
    }
?>