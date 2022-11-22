<?php
	session_start();
	require('../library.php');

	if(isset($_SESSION['form'])){
		$form = $_SESSION['form'];
	}else{
		header('Location: index.php');
	}

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		$db = dbconnect();

		$stmt = $db -> prepare('insert into members(name, email, password, picture) values(?, ?, ?, ?)');
		if(!$stmt){
			die($db -> error);
		}

		$password = password_hash($form['password'], PASSWORD_DEFAULT);
		$stmt -> bind_param('ssss', $form['name'], $form['email'], $password, $form['image']);
		$success = $stmt -> execute();
		if(!$success){
			die($db->error);
		}

		unset($_SESSION['form']);
		header("Location: thanks.php");
	}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
	<div class="header" id="head">
        <div class="container" style="margin: auto;">
            <h1 class="jumbotron-heading">登録</h1>
        </div>
	</div>


	<div class="upper-registar-form" id="content">
        <div class="lead" id="lead">
            <div class="container">
                <div class="alert alert-primary" role="alert">
                    <ul>
                        <li><p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p></li>
                    </ul>
                </div>
            </div>
        </div>

		<div class="registar-form">
			<div class="container">
				<form action="" method="post">
					<dl>
						<dt>ニックネーム</dt>
						<dd><?php echo h($form['name']); ?></dd>
						<dt>メールアドレス</dt>
						<dd><?php echo h($form['email']); ?></dd>
						<dt>パスワード</dt>
						<dd>
							【表示されません】
						</dd>
						<dt>写真など</dt>
						<dd>
								<img src="../member_picture/<?php echo h($form['image']); ?>" width="100" alt="" />
						</dd>
					</dl>
					<div><a href="index.php?action=rewrite" class="btn btn-danger">&laquo;&nbsp;書き直す</a>
					<input type="submit" class="btn btn-primary" value="登録する" /></div>
				</form>
			</div>
		</div>

</body>

</html>