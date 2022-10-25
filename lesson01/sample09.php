<?php
    for($i = 0; $i < 366; $i++):
        $time = strtotime("+$i day");
        $day = date("n/j(D)", $time);
        echo $day. "<br>";
    endfor;
?>