<?php
$savePath = './session_save_dir/';
session_save_path($savePath);
session_start();
if(isset($_SESSION['views']))
{
    $_SESSION['views']=$_SESSION['views']+1;
}
else
{
    $_SESSION['views']=1;
}
echo "浏览量：". $_SESSION['views'];
?>