<?php require("dbconnect.php"); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メモ詳細</title>
</head>
<body>
    <?php
    $stmt = $db -> prepare('select * from memos where id=?');
    if(!$stmt){
        die($db -> error);
    }
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $stmt -> bind_param('i', $id);
    $stmt -> execute();

    $stmt -> bind_result($id, $memo, $created);
    $stmt -> fetch();
    ?>

    <div><?php echo htmlspecialchars($memo); ?></div>
</body>
</html>