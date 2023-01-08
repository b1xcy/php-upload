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
$nameErr = $passErr = "";
$name = $pass = "";

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
    //如果输入的用户名密码没问题，对密码进行hash加密
    $pass=password_hash($pass,PASSWORD_DEFAULT);
    //查询用户名是否存在
    $query = "SELECT * from info where name='".$name."'";

    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        die("用户已存在");
    }
    //确定对name和加密后的pass审查结束后插入数据库
    $insert = "INSERT INTO info "."(name,pass) "."VALUES "."('$name','$pass')";
    
    $retval = mysqli_query( $conn, $insert );
    if(! $retval )
    {
      die('注册失败 ' . mysqli_error($conn));
    }
    mysqli_close($conn); 
    die("<h1>注册成功<br></h1>"."用户名为:".$name);
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<h2>注册</h2>
<p><span class="error">* 必需字段。</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
   名字: <input type="text" name="name" value="<?php echo $name;?>">
   <span class="error">* <?php echo $nameErr;?></span>
   <br><br>
   密码: <input type="password" name="pass" value="<?php echo $pass;?>">
   <span class="error">* <?php echo $passErr;?></span>
   <br><br>
   <input type="submit" name="submit" value="提交"> 
</form>
</body>
</html>