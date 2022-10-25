<?php
$txt = "ホームページをリニューアルしました";
$success = file_put_contents("data/news.txt", $txt);

if($success){
    echo "書き込みに成功しました";
}else{
    echo "書き込みに失敗しました";
}
?>