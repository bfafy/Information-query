$(document).ready(function() {
  // 提交sbForm表单时触发事件
  $('#sbForm').submit(function(e) {
    e.preventDefault(); // 阻止表单默认提交

    var term = $(this).serialize(); // 将表单数据序列化为字符串
    $.ajax({
      url: 'shebei.php', // 提交表单数据的处理页面为当前页面 shebei.php
      type: 'POST', // 设置请求类型为 POST
      data: term, // 提交表单的数据
      success: function(response) {
        $('#content').html(response); // 将返回的数据展示在 #content 元素中
      }
    });
  });

  // 提交xxForm表单时触发事件
  $('#xxForm').submit(function(e) {
    e.preventDefault(); // 阻止表单默认提交

    var term = $(this).serialize(); // 将表单数据序列化为字符串
    $.ajax({
      url: 'xx.php', // 提交表单数据的处理页面为当前页面 xx.php
      type: 'POST', // 设置请求类型为 POST
      data: term, // 提交表单的数据
      success: function(response) {
        $('#content').html(response); // 将返回的数据展示在 #content 元素中
      }
    });
  });
});
