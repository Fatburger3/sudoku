<?php
include('inc/db.php');
include('inc/sudoku.php');

$db = getDBConnection();
if(!isset($_GET)) die("GET was empty");
if(!isset($_GET['puzzle'])) die("puzzle was empty");

$puzzle = parsePuzzle($_GET['puzzle']);

$s = getPuzzleSize($puzzle);

$stmt = $db->prepare("SELECT * FROM puzzle$s;");
$stmt->execute();
$message = "puzzle$s not found";
while($record = $stmt->fetch(PDO::FETCH_ASSOC))
{
	if($record['payload'] == $_GET['puzzle'])
	{
		$stmt = $db->prepare("UPDATE puzzle$s SET solve_count= solve_count + 1 WHERE id=:id;");
		$np = array();
		$np[':id'] = (int)$record['id'];
		$stmt->execute($np);

		$stmt = $db->prepare("UPDATE difficulty SET solve_count= solve_count + 1 WHERE difficulty_id=:id;");
		$np[':id'] = (int)$record['difficulty_id'];
		$stmt->execute($np);

		$message = "puzzle$s updated";
		break;
	}
}
echo $message;
//TODO ADD NEW PUZZLES TO THE DATABASE AUTOMATICALLY

?>