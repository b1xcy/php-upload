<?php
$savePath = './session_save_dir/';
session_save_path($savePath);
session_start();
if (!isset($_SESSION['islogin']) || $_SESSION['islogin']!==1) {
    echo ("<script>alert('未登陆，即将跳转到登陆界面');</script>");
    echo '<script>window.location.href="login.php"</script>';
    die();
}
echo($_POST['name']);
// if ($_POST['name']!=$_SESSION['name']) {
//     echo ("<script>alert('用户信息错误');</script>");
//     die();
// }
// if ($_SERVER["REQUEST_METHOD"] == "POST"){
//     echo("name=".$_POST['name']);
// }
?>