<?php
$file_pd = './conf/sjk.txt';  //需要判断的文件
if (file_exists($file_pd)) {  //判断数据库有没有安装
    header("Location: index.php");
    exit();
}else {
if(isset($_POST['tj']) && $_POST['tj'] == '123'){

$user = $_POST['user'];
$pwd = md5($_POST['pwd']);
$host = $_POST['host'];
$dbname = $_POST['dbname'];
$dbpwd = $_POST['dbpwd'];
$data = $_POST['data'];

$connsj = "mysqli_connect('$host', '$dbname', '$dbpwd', '$data');";
$file_path = "./conf/conn.php";
$file = file($file_path); // 读取文件内容
unset($file[2]); // 删除第三行
$new_content = implode('', $file); // 转换为字符串
file_put_contents($file_path, $new_content); // 写回文件

if ($handle = fopen($file_path, "a")) { // 打开文件并设置为追加模式
    fwrite($handle, $connsj); //写入变量到文件中
    fclose($handle); // 关闭文件句柄
}


    //创建MySQL服务器的连接
include "./conf/conn.php";

// 检查连接错误
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 读取SQL文件内容并执行
$sql_file = './conf/db.sql';
$sql_contents = file_get_contents($sql_file);

if ($conn->multi_query($sql_contents)) {
    do {
        // 每次从结果集中取出一个结果集中的所有行
        $conn->use_result();
    } while ($conn->more_results() && $conn->next_result());
}
$sql = "INSERT INTO users (user, pwd) VALUES ('$user', '$pwd')";
$retval = mysqli_query( $conn, $sql );
if(! $retval )
{
  die('无法插入数据: ' . mysqli_error($conn));
}
// 关闭数据库连接
$conn->close();
$filename = './conf/sjk.txt'; // 文件名
$handle = fopen($filename, 'w') or die('无法打开文件！'); // 以写方式打开文件，若打开失败则输出错误信息
$txt = "安装成功。"; // 要写入的内容
fwrite($handle, $txt); // 将内容写入文件
fclose($handle); // 关闭文件
echo "<script>alert('安装完成')</script>";
}
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>安装程序</title>
        <link rel="stylesheet" type="text/css" href="./css/install.css">
    </head>
    <body>
        <div class="zy">
            <form method="post">
            <p>程序安装</p>
            <p>管理员名称：<input type="text" name="user"></p>
            <p>管理员密码：<input type="password" name="pwd"></p>
            <p>数据库地址：<input type="text" name="host" value="127.0.0.1"></p>
            <p>数据库用户：<input type="text" name="dbname"></p>
            <p>数据库密码：<input type="password" name="dbpwd"></p>
            <p>数据库库名：<input type="text" name="data"></p>
            <input type="hidden" name="tj" value="123">
            <input class="zyin" type="submit" value="安装">
            </form>
        </div>
    </body>
</html>