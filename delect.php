<?php
$savePath = './session_save_dir/';
session_save_path($savePath);
session_start();
$name=$_POST['name'];
$filename=$_POST['filename'];
$dir='./upload/'.$name.'/';
//判断是否登陆
if (!isset($_SESSION['islogin']) || $_SESSION['islogin']!==1) {
    echo ("<script>alert('未登陆，即将跳转到登陆界面');</script>");
    echo '<script>window.location.href="login.php"</script>';
    die();
}
//判断用户是否正确
if ($name!==$_SESSION['name']) {
    echo ("<script>alert('用户信息错误');</script>");
    echo ('<script>window.location.href="index.php"</script>');
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $status=unlink($dir.$filename);
    if($status){  
    echo "<script>alert('删除成功');</script>";
    echo ('<script>window.location.href="index.php"</script>');  
    }else{  
    echo "<script>alert('删除失败');</script>";
    echo ('<script>window.location.href="index.php"</script>');
    }
}
?>