<?php
/* escape special chars */
    function h($value){
            return htmlspecialchars($value, ENT_QUOTES);
    }
?>