<!DOCTYPE HTML> 
<html>
<head>
<meta charset="utf-8">
<title>注册</title>
<style>
.error {color: #FF0000;}
</style>
</head>
<body> 

<?php
// 定义变量并默认设置为空值
$name = $pass = "";

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

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (empty($_POST["name"]))
    {
        echo ("<script>alert('请输入用户名');</script>");
        echo ('<script>window.location.href="register.php"</script>');
        die();
    }
    else
    {
        $name = test_input($_POST["name"]);
        // 检测名字是否只包含字母跟空格
        if (!preg_match("/^[a-zA-Z_0-9]*$/",$name))
        {
            echo ("<script>alert('别往用户名注了');</script>");
            die();
        }
        
    }
    
    if (empty($_POST["pass"]))
    {
        echo ("<script>alert('请输入密码');</script>");
        echo ('<script>window.location.href="register.php"</script>');
        die();
    }
    else
    {
        $pass = test_input($_POST["pass"]);
        if (!preg_match("/^[a-zA-Z_0-9]*$/",$pass))
        {
            echo ($pass);
            echo ("<script>alert('别注密码了');</script>");
            echo ('<script>window.location.href="register.php"</script>');
            die();
        }
    }
    //如果输入的用户名密码没问题，对密码进行hash加密
    $pass=password_hash($pass,PASSWORD_DEFAULT);
    //查询用户名是否存在
    $query = "SELECT * from info where name='".$name."'";

    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        echo ("<script>alert('用户已存在');</script>");
        echo ('<script>window.location.href="register.php"</script>');
        die();
    }
    //确定对name和加密后的pass审查结束后插入数据库
    $insert = "INSERT INTO info "."(name,pass) "."VALUES "."('$name','$pass')";
    
    $retval = mysqli_query( $conn, $insert );
    if(! $retval )
    {
      die('注册失败 ' . mysqli_error($conn));
    }
    mysqli_close($conn); 
    echo ("<script>alert('注册成功,用户名为$name');</script>");
    echo ('<script>window.location.href="login.php"</script>');
    die();
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<h1>注册</h1>
<p><span class="error">* 必需字段。</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
   名字: <input type="text" name="name" value="<?php echo $name;?>">
   密码: <input type="password" name="pass" value="<?php echo $pass;?>">
   <input type="submit" name="submit" value="提交"> 
</form>
</body>
</html>