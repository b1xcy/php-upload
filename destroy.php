<?php
$savePath = './session_save_dir/';
session_save_path($savePath);
session_start();
echo $_SESSION['name'];
?>