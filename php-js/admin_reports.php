<!DOCTYPE html>

<?php
include('admin_session.php');
include('inc/layout.php');
include('inc/header.html');
echo makeAdminNav("Reports", "admin_reports.php");


$db = getDBConnection();
$stmt = $db->prepare("SELECT * FROM difficulty;");
$stmt->execute();

$diffs = array();

while($x = $stmt->fetch())
{
	$diffs[]=$x;
	echo '<label></label>';
}


?>

<?php include('inc/footer.html')?>