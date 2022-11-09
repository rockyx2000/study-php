<?php
    session_start();
    require('../library.php');

    if(isset($_GET['action']) && $_GET['action'] === 'rewrite' && isset($_SESSION['form'])){
        $form = $_SESSION['form'];
    }else{
        $form = [
            "name" => "",
            "email" => "",
            "password" => "",
            "image" => ""
        ];
    }
    $error = [];


    /* form validation */
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $form['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $image = $_FILES['image'];

        if($form['name'] === ""){
            $error['name'] = "blank";
        }

        if($form['email'] === ""){
            $error['email'] = "blank";
        }else{
            $db = dbconnect();
            $stmt = $db -> prepare('select count(*) from members where email=?');
            if(!$stmt){
            die($db -> error);
            }
            $stmt -> bind_param('s', $form['email']);
            $success = $stmt -> execute();
            if(!$success){
                die($db -> error);
            }
            $stmt -> bind_result($cnt);
            $stmt -> fetch();
            if($cnt > 0){
                $error['email'] = "duplicate";
            }
        }

        if($form['password'] === ""){
            $error['password'] = "blank";
        }else if(strlen($form['password']) < 4){
            $error['password'] = "length";
        }

        /* check image */
        if($image['name'] !== '' && $image['error'] === 0){
            $type = mime_content_type($image['tmp_name']);
            if($type !== 'image/png' && $type !== 'image/jpeg' && $type !== 'image/jpg'){
                $error['image'] ='type';
            }
        }

        if(empty($error)){
            $_SESSION['form'] = $form;
            if($image['name'] !== ''){
                $filename = date('YmdHis'). '_'. $image['name'];
                if(!move_uploaded_file($image['tmp_name'], '../member_picture/'. $filename)){
                    die('ファイルのアップロードに失敗しました');
                }
                $_SESSION['form']['image'] = $filename;
            }else{
                $_SESSION['form']['image'] = '';
            }

            header("Location: check.php");
            exit();
        }
    }

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ユーザー登録</title>

    <!-- <link rel="stylesheet" href="style.css"/> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
<div class="header" id="wrap" style="margin: 50px 0px 50px 0px;">
    <div class="container" id="head">
        <h1>ユーザー登録</h1>
    </div>
</div>

    <div class="join-form" id="content">
        <div class="container">
            <p>次のフォームに必要事項をご記入ください。</p>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="name" style="margin: 20px 0px 20px 0px;">
                    <label for="name" class="form-label">ニックネーム *</label>
                        <input type="text" id="name" name="name" size="35" maxlength="255" value="<?php echo h($form['name']); ?>" class="form-control"/>
                            <?php if(isset($error['name']) && $error['name'] === "blank"): ?>
                                <div class="alert alert-danger" role="alert" style="width: 400px; height: 38px; margin: 15px 0px 15px 20px; padding: 5px;">
                                    <p class="error" style="margin: 0px;">・ニックネームを入力してください</p>
                                </div>
                            <?php endif; ?>
                </div>

                <div class="email" style="margin: 20px 0px 20px 0px;">
                    <label for="email" class="form-label">メールアドレス *</label>
                    <input type="text" id="email" name="email" size="35" maxlength="255" value="<?php echo h($form['email']); ?>" class="form-control"/>
                        <?php if(isset($error['email']) && $error['email'] === "blank"): ?>
                            <div class="alert alert-danger" role="alert" style="width: 400px; height: 38px; margin: 15px 0px 15px 20px; padding: 5px;">
                                <p class="error" style="margin: 0px;">・メールアドレスを入力してください</p>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($error['email']) && $error['email'] === "duplicate"): ?>
                            <div class="alert alert-danger" role="alert" style="width: 450px; height: 38px; margin: 15px 0px 15px 20px; padding: 5px;">
                                <p class="error" style="margin: 0px;">・指定されたメールアドレスはすでに登録されています</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="password" style="margin: 20px 0px 20px 0px;">
                        <label for="password" class="form-label">パスワード *</label>
                        <input type="password" id="password" name="password" size="10" maxlength="20" value="<?php echo h($form['password']); ?>" class="form-control"/>
                            <?php if(isset($error['password']) && $error['password'] === "blank"): ?>
                                <div class="alert alert-danger" role="alert" style="width: 400px; height: 38px; margin: 15px 0px 15px 20px; padding: 5px;">
                                    <p class="error" style="margin: 0px;">・パスワードを入力してください。</p>
                                </div>
                            <?php endif; ?>

                            <?php if(isset($error['password']) && $error['password'] === "length"): ?>
                                <div class="alert alert-danger" role="alert" style="width: 400px; height: 38px; margin: 15px 0px 15px 20px; padding: 5px;">
                                    <p class="error" style="margin: 0px;">・パスワードは4文字以上で入力してください。</p>
                                </div>
                            <?php endif; ?>
                    </div>

                    <div class="image" style="margin: 20px 0px 20px 0px;">
                        <label for="image" class="form-label">プロフィール画像</label>
                        <input type="file" id="image" name="image" size="35" value="" class="form-control"/>
                            <?php if(isset($error['image']) && $error['image'] === "type"): ?>
                                <div class="alert alert-danger" role="alert" style="width: 500px; height: 38px; margin: 15px 0px 15px 20px; padding: 5px;">
                                    <p class="error" style="margin: 0px;">・写真などは「.png」または「.jpg」の画像を指定してください</p>
                                </div>
                                <p class="error"></p>
                            <?php endif; ?>

                            <?php if(isset($_GET['action']) && $_GET['action'] === 'rewrite' && isset($_SESSION['form'])): ?>
                                <div class="alert alert-danger" role="alert" style="width: 400px; height: 38px; margin: 15px 0px 15px 20px; padding: 5px;">
                                    <p class="error" style="margin: 0px;">・再選択してください。</p>
                                </div>
                            <?php endif; ?>
                    </div>


                    <div class="button" style="margin-top: 30px;">
                        <input type="submit" class="btn btn-primary" value="入力内容を確認する"/>
                    </div>
            </form>
        </div>
    </div>
</body>

</html>