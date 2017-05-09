<!DOCTYPE html>

<?php
include('admin_session.php');
include('inc/layout.php');
include('inc/header.html');
echo makeAdminNav("Reports", "admin_reports.php");


$db = getDBConnection();

$diffs = array();


?><h3>Puzzle sizes</h3><?php
function sizeData($table)
{
	echo "<h4>$table</h4>";
	global $db;
	$stmt = $db->prepare("SELECT * FROM $table;");
	$stmt->execute();
	while($x = $stmt->fetch())
	{

	}

	$stmt = $db->prepare("SELECT AVG(solve_count) FROM $table;");
	$stmt->execute();
	foreach($stmt->fetchAll() as $x)
	{
		echo "<label>Average solves:</label> $x[0]";
	}
}

$tables = getTables();
$MAX_PUZZLE_SIZE = 1;
foreach($tables as $table)
{
	if(strpos($table, 'puzzle') !== false)
	{
		$MAX_PUZZLE_SIZE++;
		sizeData($table);

	}
}


?>
<h3>Difficulties</h3>
<?php
$stmt = $db->prepare("SELECT * FROM difficulty;");
$stmt->execute();
while($x = $stmt->fetch())
{
	$d = $x['difficulty_id'];
	$n = $x['difficulty'];
	$solves = $x['solve_count'];
	$access = $x['access_count'];
	echo "<h4 class='diff$d'>$n:</h4>";
	echo "<label>Solves: </label> $solves";
	echo "<br/>";
	echo "<label>Accesses: </label> $access";
	echo "<br/>";
	for($i=2;$i<=$MAX_PUZZLE_SIZE;$i++)
	{
		$sql = "SELECT AVG(solve_count) FROM puzzle$i WHERE difficulty_id='$d'";
		$stmt2 = $db->prepare($sql);
		$stmt2->execute();
		foreach($stmt2->fetchAll() as $y)
		{
			echo "<label>Average solves(puzzle$i): </label> ". $y[0];
			echo "<br/>";
		}
	}
	for($i=2;$i<=$MAX_PUZZLE_SIZE;$i++)
	{
		$sql = "SELECT AVG(access_count) FROM puzzle$i WHERE difficulty_id=$d";
		$stmt2 = $db->prepare($sql);
		$stmt2->execute();
		foreach($stmt2->fetchAll() as $y)
		{
			echo "<label>Average accesses(puzzle$i): </label> ". $y[0];
			echo "<br/>";
		}
	}
}




?>

<?php include('inc/footer.html')?>