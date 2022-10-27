<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample04</title>
</head>
<body>
    <?php
        $db = new mysqli("localhost:8889", "root", "root", "mydb");
        $message = "フォームからのメモです";
        $stmt = $db -> prepare('insert into memos(memo) values(?)');
        if(!$stmt):
            die($db -> error);
        endif;
        $stmt -> bind_param('s', $message);
        $ret = $stmt -> execute();
        
        if($ret){
            echo "データを挿入しました";
        }else{
            echo $db -> error;
        }
        
    ?>
</body>
</html>