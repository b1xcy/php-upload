<?php
$savePath = './session_save_dir/';
session_save_path($savePath);
session_start();
if (!isset($_POST['filename']) || !isset($_POST['name'])) {
    echo ("<script>alert('请求方式有误');</script>");
    echo '<script>window.location.href="index.php"</script>';
    die();
}
$filename=$_POST['filename'];
$name=$_POST['name'];
$dir='./upload/'.$name.'/'.$filename;
if (!isset($_SESSION['islogin']) || $_SESSION['islogin']!==1) {
    echo ("<script>alert('未登陆，即将跳转到登陆界面');</script>");
    echo '<script>window.location.href="login.php"</script>';
    die();
}
if ($name!==$_SESSION['name']) {
    echo ("<script>alert('用户信息错误');</script>");
    echo ('<script>window.location.href="index.php"</script>');
    die();
}
$file=fopen($dir,"rb");
Header ( "Content-type: application/octet-stream" );
Header ( "Accept-Ranges: bytes" );
Header ( "Accept-Length: " . filesize ( $dir ) );
Header ( "Content-Disposition: attachment; filename=" . $filename );
echo fread ( $file, filesize ( $dir ) );    
fclose ( $file );    
exit ();
?>