<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>結果</title>
</head>
<body>
    <h2>ご予約日</h2>
    <?php if (!empty($_POST["reservr"])): ?>
        <?php $reserves = $_POST["reserve"]; ?>
        <ul>
            <?php foreach ($reserves as $item): ?>
                <li><?php echo htmlspecialchars($item, ENT_QUOTES); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>日付をチェックしてください</p>
    <?php endif; ?>
</body>
</html>