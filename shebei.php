<!DOCTYPE html>
<html>
  <head>
    <title>设备信息查询</title>
    <link rel="stylesheet" type="text/css" href="css/xx.css">
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/search.js"></script>
  </head>
  <body>
    <form id="sbForm">
      <input type="text" name="name">
      <input type="submit" value="提交">
    </form>

    <table id="content">
      <tr>
        <th>厂商</th>
        <th>用户名</th>
        <th>密码</th>
      </tr>

      <?php
      // 开启会话
      session_start();

      // 判断用户是否登录
      include "conf/pdlog.php";

      // 连接数据库
      include "conf/conn.php";

      if (isset($_POST['name']) && !empty($_POST['name'])) {
        $term = $_POST['name'];
        if (is_numeric($term)) {
            if (strlen($term) != 11) {
            die();
            }
        }else {
        $sql = "SELECT * FROM shebei WHERE CONCAT(cs, user, pwd) LIKE CONCAT('%', ?, '%')";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $term);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_array($result)) {
          echo '<tr>';
          echo '<td>' . $row['cs'] . '</td>';
          echo '<td>' . $row['user'] . '</td>';
          echo '<td>' . $row['pwd'] . '</td>';
          echo '</tr>';
        }
        $stmt->close();
        $conn->close();
      }
      }
?>
    </table>
  </body>
</html>