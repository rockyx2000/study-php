<?php
    session_start();
    require("library.php");
    $db = dbconnect();

    if(isset($_SESSION['id']) && isset($_SESSION['name'])){
        $id = $_SESSION['id'];
        $name = $_SESSION['name'];
    }else{
        header("Location: login.php");
        exit();
    }

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if(!$id){
        header("Location: index.php");
        exit();
    }


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ひとこと掲示板</title>

    <!-- <link rel="stylesheet" href="style.css"/> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <div class="header" style="margin-bottom: 40px;">
        <section class="jumbotron text-center">
            <div class="container">
                <h1 class="jumbotron-heading">ひとこと掲示板</h1>
            </div>
        </section>
    </div>
    <div class="container">
        <?php 
        $stmt_posts = $db -> prepare('select p.id, p.member_id, p.message, p.created, m.name, m.picture from posts p, members m where p.id=? and m.id = p.member_id order by id desc');
        if(!$stmt_posts){
            die($db -> error);
        }
        $stmt_posts -> bind_param('i', $id);
        $success = $stmt_posts -> execute();
        if(!$success){
            die($db -> error);
        }

        $stmt_posts -> bind_result($id, $member_id, $message, $created, $name, $picture);

        if($stmt_posts -> fetch()):
        ?>
            <div class="image">
                <?php if($picture): ?>
                    <img src="member_picture/<?php echo h($picture); ?>" class="rounded float-start" width="100px" height="100px" style="margin: 0px 20px 0px 0px;" alt=""/>
                <?php else: ?>
                    <img src="images/default.png" class="rounded float-start" width="100px" height="100px" style="margin: 0px 20px 0px 0px;" alt="default"/>
                <?php endif; ?>
            </div>
            <p><?php echo h($message); ?></p>
            <p class="text-muted"><?php echo h($name); ?></p>
            <p class="day"><?php echo h($created) ?></p>
        <?php else: ?>
            <p>その投稿は削除されたか、URLが間違えています</p>
        <?php endif; ?>
        <div class="button" style="margin-top: 50px;">
            <a href="index.php" class="btn btn-secondary">もどる</a>
        </div>
    </div>
</body>

</html>