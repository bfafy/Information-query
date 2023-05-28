<?php
//安装数据库
$file_path = './conf/sjk.txt';  //需要判断的文件
if (file_exists($file_path)) {  //判断数据库有没有安装
// 开启会话
session_start();

include "conf/inlog.php";

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['captcha'])){
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $captcha = $_POST['captcha'];

    // 验证码不区分大小写比较
    if (isset($captcha) && strtolower($captcha) !== strtolower($_SESSION['code'])) {
        echo "<script>alert('验证码错误！');</script>";
        unset($_SESSION['code']); // 从会话中删除验证码
    } else {
        // 防止 SQL 注入攻击
        include "conf/conn.php";
        $stmt = $conn->prepare("SELECT * FROM users WHERE user=? AND pwd=?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if(mysqli_num_rows($result) == 1){
            //登录成功，将用户最新最新登录时间做更新
            $login_time = date("Y-m-d H:i:s");
            $stmt = $conn->prepare("UPDATE users SET update_time=? WHERE user=?");
            $stmt->bind_param("ss", $login_time, $username);
            $stmt->execute();
            // 登录成功，生成加密的session并存储到sessions表中
            $key = "My_key_is_th_key"; // 加密密钥(16长度，否则报错)
            $encrypted_session = base64_encode(openssl_encrypt($username, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $key)); // 使用密钥加密
            $expire_time = time() + 1800; // 默认 session 存活时间为30分钟
            
            // 查询 sessions 表中是否存在与当前用户相关的记录
            $sql = "SELECT * FROM sessions WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // 如果有记录，则更新现有记录
                $row = $result->fetch_assoc();
                $session_id = $row['id'];
                $sql = "UPDATE sessions SET session=?, expire_time=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sii", $encrypted_session, $expire_time, $session_id);
                if ($stmt->execute()) {
                    // 更新成功
                } else {
                    // 更新失败
                }
            } else {
                // 如果没有记录，则插入新的记录
                $sql = "INSERT INTO sessions (username, session, expire_time) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $username, $encrypted_session, $expire_time);
                if ($stmt->execute()) {
                    // 插入成功
                } else {
                    // 插入失败
                }
            }

            // 登录成功，将加密的session保存在cookie中，方便下次登录时检测是否已经登录过
            setcookie("session", $encrypted_session, time()+1800, "/");
            $_SESSION['username'] = $username; // 将用户名存储到session中
            header("Location: page.php");
            exit();
        } else {
            echo "<script>alert('用户名或密码错误！');</script>";
        }
        // 及时关闭数据库连接和语句对象
        $stmt->close();
        $conn->close();
    }
}
}else{
    header('Location: install.php');    //未安装，则重定向到install页面
}

?>

<html>
<head>
  <meta charset="UTF-8">
  <title>登录</title>
  <link rel="stylesheet" href="./css/style.css">
</head>
<body>
  <div class="container">
    <h1>登录</h1>
    <form onsubmit="return validateLoginForm()" method="post">
      <label for="username">用户名：</label>
      <input type="text" name="username" id="username">

      <label for="password">密码：</label>
      <input type="password" name="password" id="password">

      <label for="captcha">验证码：</label>
      <div class="captcha-container">
        <input type="text" name="captcha" id="captcha">
        <img src="conf/captcha.php" alt="点击刷新验证码" onclick="this.src='conf/captcha.php?'+Math.random()">
      </div>
      <input type="submit" value="登录">
      &nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" value="注册" onclick="location.href='create.html'">
      <br>
      <span id="error-message"></span> <!-- 错误消息将显示在这里 -->
    </form>
  </div>
  <script src="./js/main.js"></script>
</body>
</html>