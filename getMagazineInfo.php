<table class="sortableTable">
	<thead>
	<tr>
		<th class="sortableCol"  valuetype="number">序号</th>
		<th class="sortableCol">姓名</th>
		<th class="sortableCol"  valuetype="number">密码</th>
		<th class="sortableCol"  valuetype="number">操作</th>
	</tr>
	</thead>
	<tbody>
<?php
//数据库连接
define('DB_HOST', 'localhost');  //地址
define('DB_USER', 'root');       //用户名
define('DB_PWD', '');            //密码
define('DB_NAME', 'pmanage');    //数据库名

// echo $_POST['username'];

if(!mysql_connect(DB_HOST,DB_USER,DB_PWD)){
  // $con = mysql_connect(DB_HOST,DB_USER,DB_PWD);
  exit('数据库连接失败！');
}
if(!mysql_select_db(DB_NAME)){
  exit('找不到数据库：'.DB_NAME);
}
if(!mysql_query('set names utf8')){
  exit('设置字符集出现错误！');
}
// echo mysql_query("SELECT * FROM pm_users");

if($rows=mysql_fetch_array(mysql_query("SELECT pm_name,pm_password FROM pm_users
    WHERE
    pm_name='{$_POST['username']}' AND pm_password='{$_POST['password']}' LIMIT 1"))){
      echo '登录成功，欢迎你回来：'.$rows[0];
    }else{
      echo '用户名或密码错误，忘记密码可请管理员重置';
    }

echo '<br>其他注册的人还有：<br>';
$res = mysql_query("SELECT pm_fid,pm_name,pm_password FROM pm_users ");

for($i=1;$i<=12;$i++){
  $rows2=mysql_fetch_array($res);
  if($rows2['pm_name']=='')break;
  echo "<tr><td>$i</td><td>{$rows2['pm_name']}</td><td>{$rows2['pm_password']}</td><td><a href='delete.php?id={$rows2['pm_fid']}'>delete</a> <a href='modify.php?id={$rows2['pm_fid']}'>modify</a></td></tr>";
}

?>

	</tbody>
</table>
