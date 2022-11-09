<?php
    session_start();
    require("library.php");
    $error = [];
    $email = "";
    $password = "";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        if($email === "" || $password === ""){
            $error['login'] = 'blank';
        }else{
            //logincheck
            $db = dbconnect();
            $stmt = $db -> prepare('select id, name, password from members where email=? limit 1');
            if(!$stmt){
                die($db -> error);
            }
            $stmt -> bind_param('s', $email);
            $success = $stmt -> execute();
            if(!$success){
                die($db -> error);
            }

            $stmt -> bind_result($id, $name, $hash);
            $stmt -> fetch();

            if(password_verify($password, $hash)){
                //success
                session_regenerate_id();
                $_SESSION['id'] = $id;
                $_SESSION['name'] = $name;
                header("Location: index.php");
                exit();
            }else{
                $error['login'] = "failed";
            }
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ログインする</title>

    <!-- <link rel="stylesheet" href="style.css"/> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <div class="header" id="head">
        <div class="container" style="margin: auto;">
            <h1 class="jumbotron-heading">ログインする</h1>
        </div>
    </div>
    <div class="login-form" id="content">
        <div class="lead" id="lead">
            <div class="container">
                <div class="alert alert-primary" role="alert">
                    <ul>
                        <li><p>メールアドレスとパスワードを記入してログインしてください。</p></li>
                        <li><p>ユーザー登録がまだの方は<a href="join/" class="alert-link">こちら</a>からどうぞ。</p></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="form">
            <div class="container">
                <form action="" method="post">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input type="text" id="email" name="email" size="35" maxlength="255" value="<?php h($email); ?>" class="form-control"/>
                        <?php if(isset($error['login']) && $error['login'] === "blank"): ?>
                            <div class="alert alert-danger" role="alert" style="width: 400px; height: 38px; margin: 15px 0px 15px 20px; padding: 5px;">
                                <p class="error" style="margin: 0px;">* メールアドレスとパスワードをご記入ください</p>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($error['login']) && $error['login'] === "failed"): ?>
                            <div class="alert alert-danger" role="alert" style="width: 450px; height: 38px; margin: 15px 0px 15px 20px; padding: 5px;">
                                <p class="error" style="margin: 0px;">
                                    * ログインに失敗しました。正しくご記入ください。
                                </p>
                            </div>
                        <?php endif; ?>
                    <label for="password" class="form-label">パスワード</label>
                    <input type="password" id="password" name="password" size="35" maxlength="255" value="<?php h($password); ?>" class="form-control"/>
                    <div class="button" style="margin-top: 30px;">
                        <input type="submit" value="ログインする" class="btn btn-primary"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
