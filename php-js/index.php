<!DOCTYPE html>
<?php
include('inc/layout.php');
include('inc/header.html');
include('action_open.php');
echo makeNav('Sudoku', 'index.php');

$MAX_PUZZLE_SIZE = 8;

$size = 3;
if(isset($_GET['size']))
{
	$size = $_GET['size'];
}

if(isset($_GET['puzzle']))
{
	$puzzle = parsePuzzle($_GET['puzzle']);
}
else
{
	$puzzle = getEmptyPuzzle($size);
}

?>
<br/>
<form class="form-group centered-form">
	<div class="input-group">
		<select class="form-control" name='size'>
			<?php
					for($s=2;$s<=$MAX_PUZZLE_SIZE;$s++)
					{
						echo "<option value=$s";
						echo ($s == $size)?" selected='selected'>":">";
						if($s === 2)
						{
							echo "Basic puzzle(4x4)";
						}
						else if($s === 3)
						{
							echo "Sudoku puzzle(9x9)";
						}
						else if($s === 4)
						{
							echo "Hexadoku puzzle(16x16)";
						}
						else if($s === $MAX_PUZZLE_SIZE)
						{
							echo "Okay, stop, you're gonna break the webpage";
						}
						else
						{
							$t = $s * $s;
							echo $t."X".$t." puzzle";
						}
						echo "</option>";
					}
			?>
		</select>
		<span class="input-group-btn">
			<button class="btn btn-primary form-control">Go</button>
		</span>
	</div>
</form>

<br/>
<div class="btn-group form-group" role="group" aria-label="Basic example">
	<button class="btn btn-success" onclick="solvePuzzleForm(); return false;">Solve</button>
	<button class="btn btn-warning" onclick="clearInputs(); return false;">Clear</button>
</div>


<?php
echo makePuzzleForm($puzzle);
include('inc/footer.html');?>