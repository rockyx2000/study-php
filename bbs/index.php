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

    //postmessages
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
        $stmt = $db -> prepare('insert into posts (message, member_id) values(?, ?)');
        if(!$stmt){
            die($db -> error);
        }
        $stmt -> bind_param('si', $message, $id);
        $success = $stmt -> execute();
        if(!$success){
            die($db -> error);
        }

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
    <section class="jumbotron" style=" margin: 30px 0px 30px 0px; text-align: center;">
        <div class="container" style="">
            <h1 class="jumbotron-heading">ひとこと掲示板</h1>
        </div>
    </section>
    <!-- Button trigger modal -->
    <div class="post-create" style="margin: 20px 0px 20px 0px; text-align: center;">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createpost">
            投稿する
        </button>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="createpost" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo h($name); ?>さん、メッセージをどうぞ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="sentence" class="form-label"></label>
                            <textarea id="sentence" class="form-control w-100" name="message" cols="50" rows="5"></textarea>
                        </div>
                        <input type="submit" class="btn btn-primary" value="投稿"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <div style="margin-right: 20px; text-align: right">
            <a href="logout.php" class="btn btn-danger">
                <i class="bi bi-box-arrow-left" style=""></i> 
                ログアウト
            </a>
            <?php echo h($name); ?>
        </div>
            <div class="album py-5">
                <div class="container">
                    <div class="row">
                    <?php 
                        $stmt_posts = $db -> prepare('select p.id, p.member_id, p.message, p.created, m.name, m.picture from posts p, members m where m.id = p.member_id order by id desc');
                        if(!$stmt_posts){
                            die($db -> error);
                        }

                        $success = $stmt_posts -> execute();
                        if(!$success){
                            die($db -> error);
                        }

                        $stmt_posts -> bind_result($id, $member_id, $message, $created, $name, $picture);

                        while($stmt_posts -> fetch()):
                    ?>
                        <div class="col-md-4" style="margin-bottom: 50px;">
                            <div class="card mb-4 shadow-sm" style="width: 19rem; margin: auto;">
                                <?php if($picture != NULL): ?>
                                    <img src="member_picture/<?php echo h($picture); ?>" class="card-img-top" style="height: 225px; width: 100%; display: block;" alt=""/>
                                <?php else: ?>
                                    <img src="images/default.png" class="card-img-top" style="height: 225px; width: 100%; display: block;" alt="default"/>
                                <?php endif; ?>
                                <div class="card-body">
                                    <p class="card-title"><?php echo h($message); ?></p>
                                    <p class="text-muted"><?php echo h($name); ?></p>
                                    <p class="day">投稿: <?php echo h($created) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <a href="view.php?id=<?php echo h($id); ?>" class="btn btn-primary">
                                                詳細へ
                                            </a>
                                        </div>
                                        <?php if($_SESSION['id'] === $member_id): ?>
                                            <div class="dropdown">
                                                <button class="btn" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <div class="post-button">
                                                        <i class="bi bi-gear"></i>
                                                    </div>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                    <li><a href="#" class="dropdown-item">編集</a>
                                                    <li><a href="delete.php?id=<?php echo h($id); ?>" class="dropdown-item" onclick="return confirm('本当に削除しますか?')">削除</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
</body>

<script>
    function confDel(){
        window.confirm("本当に削除しますか？");
    }
</script>

</html>