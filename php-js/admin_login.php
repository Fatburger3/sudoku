<!DOCTYPE html>
<?php
session_start();
include('inc/db.php');
include('inc/layout.php');
include('inc/header.html');
echo makeNav('Admin Login', 'admin_index.php');

if($_SERVER["REQUEST_METHOD"] === "POST")
{
	// username and password sent from form
	$db=getDBConnection();
	$q = "SELECT name FROM admin WHERE name = :u AND pass = SHA1(:p)";

	$np = [];
	$np [':u']= $_POST['name'];
	$np [':p']= $_POST['pass'];

	$stmt = $db->prepare($q);
	$stmt->execute($np);
	$count = $stmt->rowCount();
	$myusername = $stmt->fetch()['name'];

	// If result matched $myusername and $mypassword, table row must be 1 row
	if($count === 1)
	{
		//session_register("myusername");
		$_SESSION['login_user'] = $myusername;
		header("location: admin_index.php");
	}
	else
	{
		$error = "Your username or password is invalid";
	}
}
?>
<div align="center">
	<div id="login_block" align="left">

		<div class="block_header">
		</div>

		<div style="margin-left:30%;margin-right:30%;">

			<form method='POST'>

				<div class="form-group">
					<input type="text" name="name" placeholder="Username" class="form-control">
				</div>

				<div class="form-group">
					<input type="password" name="pass" placeholder="Password" class="form-control">
				</div>

				<div class="form-group">
					<input type="submit" name="login" class="btn btn-primary" value="Login">
				</div>

			</form>

			<div style="color:red;">
				<?php echo $error; ?>
			</div>

		</div>

	</div>
</div>
<?php include('inc/footer.html')?>