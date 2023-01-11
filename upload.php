<?php
$banlist=array('php','htaccess','aspx','pht', 'phpt', 'phtml', 'php3','php4','php5','php6','user');

$savePath = './session_save_dir/';
session_save_path($savePath);
session_start();

$name=$_POST['name'];
//判断用户是否登陆
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

//判断上传是否出错
if ($_FILES['file']['error']>0) {
    echo("<script>alert('上传出错')</script>");
    echo('<script>window.location.href="index.php"</script>');
    die();
}

//判断文件名中是否有黑名单中字符
foreach ($banlist as $ban){
    if (stristr($_FILES["file"]["name"],$ban)) {
        echo "<script>alert('别绕了')</script>";
        die();
    }
}
if (file_exists("upload/" . $_FILES["file"]["name"])){
    echo "<script>alert('".$_FILES["file"]["name"]."已存在,删除后重新上传')</script>";
    echo '<script>window.location.href="index.php"</script>';
    die();
}
//完成上传
move_uploaded_file($_FILES["file"]["tmp_name"],"./upload/".$name.'/'.$_FILES["file"]["name"]);
echo "<script>alert('".$_FILES["file"]["name"]."上传成功')</script>";
echo '<script>window.location.href="index.php"</script>';
?>