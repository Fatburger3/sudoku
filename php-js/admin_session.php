<?php

session_start();
include('inc/db.php');

$user_check = $_SESSION['login_user'];

if(!isset($user_check))
{
	header("location:admin_login.php");
}

$stmt = getDBConnection()->prepare("SELECT name FROM admin WHERE name=:u");
$np = array();
$np[':u'] = $user_check;
$stmt->execute($np);
$row = $stmt->fetch();
$login_session = $row['name'];

?>