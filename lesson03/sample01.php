<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample01</title>
</head>
<body>
    <?php
        $db = new mysqli("localhost:8889", "root", "root", "mydb");
        $success = $db -> query("select * from my_items");
        if($success){
            echo "テーブルを表示しました";
        }else{
            echo "SQLが正常に動作しませんでした";
            echo $db -> error;
        }
        

    ?>
</body>
</html>