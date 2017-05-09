<!DOCTYPE html>
<?php
include('inc/layout.php');
include('inc/header.html');
echo makeNav('Search', 'search.php');

$puzzle_size = 3;
$dif = 1;

if(isset($_GET['size']))
{
	$puzzle_size = $_GET['size'];
}

if(isset($_GET['dif']))
{
	$puzzle_size = $_GET['dif'];
}

$puzzle_size *= $puzzle_size;
$puzzle_size *= $puzzle_size;

$puzzle = array(0);

for($i = 1;$i < $puzzle_size; $i++)
{
	$puzzle []= 0;
}

?>



<?php

echo makePuzzleForm($puzzle);

include('inc/footer.html');
?>