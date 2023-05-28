<?php
//获取用户注册、最新登录时间
include "conn.php";
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = mysqli_fetch_assoc($result);
$reg_time = htmlspecialchars($row['registration_time'], ENT_QUOTES, 'UTF-8');
$up_time = htmlspecialchars($row['update_time'], ENT_QUOTES, 'UTF-8');
$stmt->close();
$conn->close();