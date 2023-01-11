<?php
// 定义变量并默认设置为空值
$name = $pass = $pass_hash = "";
$savePath = './session_save_dir/';

session_save_path($savePath);

session_start();
if (isset($_SESSION['islogin']) && $_SESSION['islogin']===1) {
    echo ("<script>alert('你已登陆');</script>");
    echo ('<script>window.location.href="index.php"</script>');
    die();
}
$_SESSION['islogin']=null;
//设置数据库
$dbhost = 'localhost';  // mysql服务器主机地址
$dbuser = 'root';            // mysql用户名
$dbpass = 'root';          // mysql用户名密码
$dbname = 'user';   //mysql数据库
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if(! $conn )
{
  die('连接失败: ' . mysqli_error($conn));
}
// 设置编码，防止中文乱码
mysqli_query($conn , "set names utf8");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (empty($_POST["name"]))
    {
        $nameErr = "输入名字以登陆";
    }
    else
    {
        $name = test_input($_POST["name"]);
        // 检测名字是否只包含字母跟空格
        if (!preg_match("/^[a-zA-Z_]*$/",$name))
        {
            echo ("<script>alert('别注了');</script>");
            die();
            //$nameErr = "仅允许字母下划线,别注入了"; 
        }
        
    }
    
    if (empty($_POST["pass"]))
    {
      $passErr = "输入密码以登陆";
    }
    else
    {
        $pass = test_input($_POST["pass"]);
        if (!preg_match("/^[a-zA-Z_.?]*$/",$pass))
        {
            echo ("<script>alert('别注了');</script>");
            die();
            //$passErr = "别注了"; 
        }
    }
    //查询表中存储的hash密码
    $query = "SELECT pass from info where name='".$name."'";
    $result = mysqli_query($conn, $query);    
    if (mysqli_num_rows($result) > 0) {
        $pass_hash = mysqli_fetch_assoc($result)['pass'];
        if (password_verify($pass,$pass_hash)) {
            echo("<script>alert('登陆成功');</script>");
            $_SESSION['islogin']=1;
            $_SESSION['name']=$name;
            echo '<script>window.location.href="index.php"</script>';
            die();
        }
        else {
            echo("<script>alert('密码错误');</script>");
            echo '<script>window.location.href="login.php"</script>';
        }
    }else {
        echo("<script>alert('用户名错误');</script>");
        echo '<script>window.location.href="login.php"</script>';
    }
}
mysqli_close($conn); 

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<html>
<head>
<meta charset="utf-8">
<title>登陆</title>
<body>
<h1>登陆</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
   名字: <input type="text" name="name" value="<?php echo $name;?>">
   密码: <input type="password" name="pass" value="<?php echo $pass;?>">
   <input type="submit" name="submit" value="提交"> 
</form>
<div style="text-align:center">
<input type="button" value="注册" onclick="location.href='register.php'"></div>
</body>
</html>