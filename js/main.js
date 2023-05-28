// 显示错误消息
function showError(message) {
  var errorMessage = document.getElementById("error-message");
  errorMessage.innerText = message;
  errorMessage.style.color = "red";
}

// 验证登录表单
function validateLoginForm() {
  var username = document.getElementById("username").value;
  var password = document.getElementById("password").value;
  var captcha = document.getElementById("captcha").value;

  if (username == "" || password == "" || captcha == "") {
    showError("请填写完整的用户名、密码和验证码！");
    return false;
  }

  if (password.length < 6) {
    showError("密码长度必须大于等于6位！");
    return false;
  }

  if (captcha.length != 4) {
    showError("请输入4位验证码！");
    return false;
  }

  // 如果用户名、密码和验证码都不为空，且密码长度大于等于6位，并且验证码长度为4，则返回 true，允许提交表单
  return true;
}

// 验证注册表单
function validateRegisterForm() {
  var username = document.getElementById("username").value;
  var password = document.getElementById("password").value;
  var confirmPassword = document.getElementById("confirm-password").value;
  var captcha = document.getElementById("captcha").value;

  if (username == "" || password == "" || confirmPassword == "" || captcha == "") {
    showError("请填写完整的注册信息和验证码！");
    return false;
  }

  if (password.length < 6) {
    showError("密码长度必须大于等于6位！");
    return false;
  }

  if (password !== confirmPassword) {
    showError("两次输入的密码不匹配！");
    return false;
  }

  if (captcha.length != 4) {
    showError("请输入验证码！");
    return false;
  }

  // 如果用户名、密码、确认密码和验证码都不为空，且密码长度大于等于6位，并且两次密码输入相同，并且验证码长度为4，则返回 true，允许提交表单
  return true;
}
