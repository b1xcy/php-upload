<?php
$savePath = './session_save_dir/';
session_save_path($savePath);
session_start();
if (!isset($_SESSION['islogin']) || $_SESSION['islogin']!==1) {
    echo ("<script>alert('未登陆，即将跳转到登陆界面');</script>");
    echo ('<script>window.location.href="login.php"</script>');
    die();
}
$name=$_SESSION['name'];
$dir='./upload/'.$name;
if (!is_dir($dir)) {
    mkdir ( $dir, '0777' );
}
$filename = scandir($dir);
?>
<form method="post" action="download.php">
<table border = 1>
<?php
foreach($filename as $k=>$v){
    // 跳过两个特殊目录   continue跳出循环
    if($v=="." || $v==".."){
        continue;
    }
    echo('<form method="post" action="download.php">');
    echo('<tr>');
    echo('<th><input type="submit" name="download" value="下载"></th>');
    echo("<input type='hidden' name='filename' value='$v'><input type='hidden' name='name' value='$name'></form>");
    echo('<th>'.$v.'</th>');
    echo('<form method="post" action="delect.php">');
    echo('<th><input type="submit" name="delect" value="删除"></th></tr>');
    echo("<input type='hidden' name='filename' value='$v'><input type='hidden' name='name' value='$name'></form>");
}
?>
</table></form>
<form method="post" action="upload.php" enctype="multipart/form-data">
<div style="text-align:center">
<input type="file" name="file" id="file">
<input type="submit" name="submit" value="上传">
<input type='hidden' name='name' value="<?php echo $name;?>">
</div></form>
<br>
<form method="post" action="logout.php">
<div style="text-align:center">
<input type="submit" name="delect" value="登出">
</div></form>