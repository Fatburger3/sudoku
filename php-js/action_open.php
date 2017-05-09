<?php
include('inc/db.php');

$db = getDBConnection();
if(!isset($_GET))
{
	return;
}
if(!isset($_GET['puzzle']))
{
	return;
}

$puzzle = parsePuzzle($_GET['puzzle']);

$s = getPuzzleSize($puzzle);

$stmt = $db->prepare("SELECT * FROM puzzle$s;");
$stmt->execute();
$message = "puzzle not found";
while($record = $stmt->fetch(PDO::FETCH_ASSOC))
{
	if($record['payload'] == $_GET['puzzle'])
	{
		$stmt = $db->prepare("UPDATE puzzle$s SET access_count = access_count + 1 WHERE id=:id;");
		$np = array();
		$np[':id'] = (int)$record['id'];
		$stmt->execute($np);

		$stmt = $db->prepare("UPDATE difficulty SET access_count = access_count + 1 WHERE difficulty_id=:id;");
		$np[':id'] = (int)$record['difficulty_id'];
		$stmt->execute($np);

		$message = "puzzle updated";
		break;
	}
}
// echo $message;
//TODO ADD NEW PUZZLES TO THE DATABASE AUTOMATICALLY

?>