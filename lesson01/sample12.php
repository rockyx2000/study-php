<?php
$time = date("G");

?>

<?php if ($time < 10): ?>
    <h1>※営業時間外です※</h1>
<?php else: ?> 
    <h1>ようこそ</h1>
<?php endif; ?>

<?php
$s = "a";

if ($s):
    echo "文字が入っています";
endif;
?>