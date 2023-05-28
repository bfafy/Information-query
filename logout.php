<?php
// 开启会话
session_start();
//判断用户是否登录
include "conf/pdlog.php";
// 删除 sessions 表中与当前用户相关的记录
if(isset($_SESSION['username'])) {
    include "conf/conn.php";
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("DELETE FROM sessions WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}
// 销毁 session 数据
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// 销毁 session
session_destroy();

// 重定向到登录页面或其他需要授权访问的页面
header("Location: index.php");
exit();
?>