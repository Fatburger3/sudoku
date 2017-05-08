<!DOCTYPE html>
<?php
include('inc/layout.php');
include('inc/header.html');
echo makeNav('Home', 'index.php');

$puzzle_size = 3;

if(isset($_GET['size']))
{
	$puzzle_size = $_GET['size'];
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