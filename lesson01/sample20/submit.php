<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample20</title>
</head>
<body>
    <?php if(!empty($_REQUEST["my_name"])): ?>
        <p>お名前: <?php echo htmlspecialchars($_POST["my_name"], ENT_QUOTES); ?></p>
    <?php else: ?>
        <p>お名前を入力してください</p>
    <?php endif; ?>
</body>
</html>