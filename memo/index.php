<?php
require("dbconnect.php");

$page_count = $db -> query('select count(*) as cnt from memos');
$count = $page_count -> fetch_assoc();

if($count["cnt"] % 5 == 0):
    $max_page = $count["cnt"] / 5;
else:
    $max_page = floor(($count["cnt"] + 1) / 5 + 1);
endif;

$stmt = $db -> prepare('select * from memos order by id desc limit ?, 5');
if(!$stmt){
    die($db -> error);
}

$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
$page = ($page ?: 1);
$start =($page - 1) * 5;
$stmt -> bind_param('i', $start);
$result = $stmt -> execute();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メモ帳</title>
</head>
<body>
    <h1>メモ帳</h1>

    <p>→ <a href="input.html">新しいメモ</a></p>
    <?php if(!$result): ?>
        <p>表示するメモはありません</p>
    <?php endif; ?>
    <?php $stmt -> bind_result($id, $memo, $created, $edit); ?>
    <?php while($stmt -> fetch()): ?>
        <div>
            <h2><a href="memo.php?id=<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars(mb_substr($memo, 0, 50)); ?></a>
            <?php if($edit != NULL): ?>
                <time><?php echo htmlspecialchars($edit); ?></time>
            <?php else: ?>
                <time><?php echo htmlspecialchars($created); ?></time>
            <?php endif; ?>
        </div>
        <hr>
    <?php endwhile; ?>

    <?php if($page > 1): ?>
        <a href="?page=<?php echo $page-1; ?>">戻る</a>|
    <?php endif; ?>

    <?php if($page < $max_page): ?>
        <a href="?page=<?php echo $page+1; ?>">次へ</a>
    <?php endif; ?>
</body>
</html>