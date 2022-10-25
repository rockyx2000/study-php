<?php
$zipcode = "123-4567";

if (preg_match("/\A\d{3}[-]\d{4}\z/", $zipcode)){
    echo "郵便番号: 〒". $zipcode; 
}else{
    echo "正しく入力してください";
}