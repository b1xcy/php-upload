<?php
$savePath = './session_save_dir/';
session_save_path($savePath);
session_start();

//检查是否以管理员账户登陆
if ((!isset($_SESSION['islogin']) || $_SESSION['islogin']!==1) || $_SESSION['name']!=='admin') {
    echo ("<script>alert('禁止访问');</script>");
    echo '<script>window.location.href="index.php"</script>';
    die();
}

//设置数据库
$dbhost = 'localhost';  // mysql服务器主机地址
$dbuser = 'root';            // mysql用户名
$dbpass = 'root';          // mysql用户名密码
$dbname = 'user';   //mysql数据库
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if(! $conn )
{
  die('数据库连接失败: ' . mysqli_error($conn));
}
// 设置编码，防止中文乱码
mysqli_query($conn , "set names utf8");
?>

<?php
//设置删除操作
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST['user_name'])) {
        $user_name=$_POST['user_name'];
        $drop = "delete from info where name='$user_name';";
        if(mysqli_query($conn, $drop)){  
            echo "<script>alert('删除用户".$user_name."成功');</script>";
            echo '<script>window.location.href="manage.php"</script>';
           }else{  
           echo "删除失败：". mysqli_error($conn);  
           }
    }
}
?>

<title>用户管理</title>
<h1>用户管理</h1>
<div style="text-align:center">
<table border = 1 style="margin:auto">
<?php
//查询所有用户
$query = "SELECT name from info;";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    if ($row["name"]==="admin") {
        echo '<tr>';
        echo "<th>用户:".$row["name"].'(管理员)</th>';
        echo '</tr><br>';
    }else {
        echo('<form method="post" action="manage.php">');
        echo '<tr>';
        echo "<th>用户:".$row["name"]."</th>";
        echo "<input type='hidden' name='user_name' value='".$row["name"]."'>";
        echo('<th><input type="submit" name="drop" value="删除"></th>');
        echo '</tr></form><br>';
    }
}
?>
<form method="get" action="index.php">
    <button type="submit">回到主页</button>
</form>
</div>