<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_COOKIE['session'])) {
    header("Location: index.php");
    exit(1);
}

// 准备解密 session
$encrypted_session_b64 = $_COOKIE['session'];
$key = "My_key_is_th_key";  // 加密密钥(16长度，否则报错)
$encrypted_session = base64_decode($encrypted_session_b64);
$jmsession = openssl_decrypt($encrypted_session, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $key);

// 连接到数据库并查询 session 数据（使用参数化查询）
include "conn.php";
$stmt = $conn->prepare("SELECT * FROM sessions WHERE session=?");
$stmt->bind_param("s", $encrypted_session_b64);
$stmt->execute();
$result = $stmt->get_result();

if (mysqli_num_rows($result) == 0) {
    mysqli_close($conn);
    header("Location: index.php");
    exit(2);
} else {
    $row = $result->fetch_assoc();
    if ($row['username'] !== $jmsession) {
        mysqli_close($conn);
        header("Location: index.php");
        exit(3);
    }
}

// 关闭连接和语句对象
$stmt->close();
mysqli_close($conn);
