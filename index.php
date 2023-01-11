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

//判断管理员权限
if ($name=='admin') {
    $dir='./upload/';
    if (!is_dir('./upload/admin')) {
        mkdir ( './upload/admin', '0777' );
    }
    echo '<div style="text-align:center">当前为管理员账户登陆</div>';
    echo '<form method="post" action="manage.php"><div style="text-align:center"><input type="submit" name="delect" value="用户管理"></div></form>';

}else {
    $dir='./upload/'.$name;
    if (!is_dir($dir)) {
        mkdir ( $dir, '0777' );
    }
}
$filename = scandir($dir);
?>
<table border = 1>
<?php
function fileShow($dir,$name){                           //遍历目录下的所有文件和文件夹
    $handle = opendir($dir);
    while($file = readdir($handle)){
         if($file !== '..' && $file !== '.'){
             $f = $dir.'/'.$file;
         if(is_file($f)){
                     //echo '|--'.$file.'<br>';          //代表文件
                     echo('<form method="post" action="download.php">');
                     echo('<tr>');
                     echo('<th><input type="submit" name="download" value="下载"></th>');
                     echo("<input type='hidden' name='filename' value='$file'><input type='hidden' name='name' value='$name'></form>");
                     echo('<th>'.$file.'</th>');
                     echo('<form method="post" action="delect.php">');
                     echo('<th><input type="submit" name="delect" value="删除"></th></tr>');
                     echo("<input type='hidden' name='filename' value='$file'><input type='hidden' name='name' value='$name'></form>");
                 }else{
                 //echo  '--'.$file.'<br>';          //代表文件夹
                 echo('<tr>');
                 echo('<th>--用户'.$file.'的文件↓</th>');
                 fileShow($f,$name);
             }
                 }
     }
}

fileShow("$dir",$name);
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