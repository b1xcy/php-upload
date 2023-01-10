<?php
$savePath = './session_save_dir/';
session_save_path($savePath);
session_start();
session_destroy();
echo("<script>alert('已登出')</script>");
echo('<script>window.location.href="login.php"</script>');
?>