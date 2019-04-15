<?php
$fuck=$_SERVER['DOCUMENT_ROOT'];
include $fuck.'/auto/php54n/config.php';
echo $username.'</br>';
echo $password.'</br>';
echo $dbname.'</br>';
			function showall(){
				global $username, $password ,$dbname;
				$connect=mysqli_connect('127.0.0.1',$username,$password,$dbname,'3306');
				$sql='select * from jz_user';
				mysqli_query($connect,'set names utf8');
				$result=mysqli_query($connect,$sql);
				echo "<table width='100%'>";
				echo "<tr><td>id</td><td>用户名</td><td>昵称</td><td>备注</td><td>头像</td><td>透视</td><td>房卡</td><td>胜率</td><td>到期时间</td><td>操作</td></tr>";
				while($row =mysqli_fetch_array($result)){
				echo "<tr><td>".$row['id']."</td><td>".$row['user_login']."</td><td>".$row['nickname']."</td><td>".$row['disable_notice']."</td><td><img src='".$row['img']."' width=50 /></td><td>".$row['is_grade']."</td><td>".$row['fk']."</td><td>".$row['gailv']."</td><td>".$row['create_time']."</td><td><a href='?id=".$row['id']."'>修改</a></td></tr>";
				}
				echo "</table>";	}
			
			function byiduser($userid){
				global $username, $password ,$dbname;
				$connect=mysqli_connect('127.0.0.1',$username,$password,$dbname,'3306');
				mysqli_query($connect,'set names utf8');
				$sql='select * from jz_user where id ='.$userid;
				$result=mysqli_query($connect,$sql);
				$row =mysqli_fetch_array($result);
				
				echo "<form action='?up=1' method='post'>";
				echo "<table>";
				echo "<input type='hidden' name='id' value='".$userid."'>";
				echo "<tr><td>微信名</td><td>".$row['nickname']."</td></tr>";
				echo "<tr><td>用户名</td><td><input name='user_login' value='".$row['user_login']."'></td></tr>";
				echo "<tr><td>头像</td><td><img src='".$row['img']."' width=50 /></td></tr>";
				echo "<tr><td>房卡</td><td><input name='fk' value='".$row['fk']."'></td></tr>";
				echo "<tr><td>胜率</td><td><input name='gailv' value='".$row['gailv']."'></td></tr>";
			    if($row['is_grade']){
					echo "<tr><td>透视</td><td><input name='is_grade' type='radio' value='1' checked />透视<input name='is_grade' type='radio' value='0'/>无透视</td></tr>";
				}else{
					echo "<tr><td>透视</td><td><input name='is_grade' type='radio' value='1' />透视<input name='is_grade' type='radio' value='0' checked/>无透视</td></tr>";
				}
				echo "<tr><td>到期</td><td><input name='create_time' value='".$row['create_time']."'></td></tr>"; 
				echo "</table>";
				echo "<input type='submit' value='确认' />";
				echo "</form>";
			}
	
			function updateuser($id,$user_login,$fk,$gailv,$is_grade,$create_time){
				global $username, $password ,$dbname;
				$connect=mysqli_connect('127.0.0.1',$username,$password,$dbname,'3306');
				mysqli_query($connect,'set names utf8');
				$sql="UPDATE jz_user SET user_login = '".$user_login."' ,fk = '".$fk."',gailv = '".$gailv."',is_grade = '".$is_grade."',create_time='".$create_time."'  WHERE id = ".$id.";";
				mysqli_query($connect,$sql);
			}
	$getid = $_GET['id'];
	$up = $_GET['up'];
	
	if(!empty($up)){
		$id = $_POST['id'];
		$user_login = $_POST['user_login'];
		$fk = $_POST['fk'];
		$gailv = $_POST['gailv'];
		$is_grade = $_POST['is_grade'];
		$create_time = $_POST['create_time'];
		updateuser($id,$user_login,$fk,$gailv,$is_grade,$create_time);
	}	
	
		if(empty($getid))
			showall();
		else{
			byiduser($getid);
		}
 ?>