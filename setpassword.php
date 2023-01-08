<?php
session_start();
if ($_SESSION['islogin']!=1 || !isset($_SESSION['islogin'])) {
    echo("<script>alert('未登陆');</script>");
    echo '<script>window.location.href="login.php"</script>';
}
// 定义变量并默认设置为空值
$name = $pass = $pass_hash = $pass_new = '';
//设置数据库
$dbhost = 'localhost';  // mysql服务器主机地址
$dbuser = 'root';            // mysql用户名
$dbpass = '20031117';          // mysql用户名密码
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
    if(empty($_POST["name"]) || empty($_POST["pass"]) ||empty($_POST["pass_new"]))
    {
        echo("请输入用户名或密码");
    }
    else 
    {
        $name = test_input($_POST["name"]);
        $pass = test_input($_POST["pass"]);
        $pass_new = test_input($_POST["pass_new"]);
        if (!preg_match("/^[a-zA-Z_]*$/",$name) || !preg_match("/^[a-zA-Z_]*$/",$pass) ||!preg_match("/^[a-zA-Z_]*$/",$pass_new)) {
            echo ("<script>alert('别注了');</script>");
            die();
        }
        //将新密码加密
        $pass_new=password_hash($pass_new,PASSWORD_DEFAULT);

        //定义查询和更新数据库的语句
        $query = "SELECT pass from info where name='".$name."'";
        $change = "UPDATE info SET pass='".$pass_new."' where name='".$name."'";
        
        $result_query = mysqli_query($conn, $query);
        //确认输入的原密码正确
        if (mysqli_num_rows($result_query) > 0) {
            $pass_hash = mysqli_fetch_assoc($result_query)['pass'];
            if (password_verify($pass,$pass_hash)) {
                $result_change = mysqli_query($conn, $change);
                echo("<script>alert('密码修改成功');</script>");
                $_SESSION['islogin']=null;
                echo '<script>window.location.href="index.php"</script>';
            }
            else {
                echo("<script>alert('原密码错误');</script>");
                echo '<script>window.location.href="setpassword.php"</script>';
            }
        }else {
            echo("<script>alert('用户名输入错误');</script>");
            echo '<script>window.location.href="setpassword.php"</script>';
        }
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
        <title>修改密码</title>
        <body>
            <h1>修改密码</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
                名字: <input type="text" name="name" value="<?php echo $name;?>">
                原密码: <input type="password" name="pass" value="<?php echo $pass;?>">
                新密码: <input type="password" name="pass_new" value="<?php echo $pass_new;?>">
                <input type="submit" name="submit" value="提交">
            </form>
        </body>
    </head>
</html>