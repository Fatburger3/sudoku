<!DOCTYPE html>
<?php
include('inc/layout.php');
include('inc/db.php');
include('inc/header.html');
echo makeNav('Search', 'search.php');

$size = 3;
//difficulty
$dif = 0;
$count = 10;
$page = 1;

if(isset($_GET['size']))
{
	$size = $_GET['size'];
}

if(isset($_GET['dif']))
{
	$dif = $_GET['dif'];
}

if(isset($_GET['count']))
{
	$count = $_GET['count'];
}

if(isset($_GET['page']))
{
	$page = $_GET['page'];
}

$tables = getTables();
$MAX_PUZZLE_SIZE = 1;
foreach($tables as $table)
{
	if(strpos($table, 'puzzle') !== false)
	{
		$MAX_PUZZLE_SIZE++;
	}
}

// Gotta prevent that injection somehow.
if($size == 1)
{
	die("Size of 1 is not allowed");
}
$injection = true;
for($i=$MAX_PUZZLE_SIZE;$i>=0;$i--)
{
	if($size == $i)
	{
		$injection = false;
		break;
	}
}

if($injection)
{
	die("SQL Injection error");
}

$start=($page - 1)*$count;
$end=$start + $count;

$where='';
$dowhere=false;
$np=array();
if($dif != 0)
{
	$where = " WHERE difficulty_id=:dif";
	$np[':dif']=$dif;
	$dowhere = true;
}

if(false)
{//Unused search where clause
	$where .= $dowhere?" WHERE ":", ";
	$where .= "";
	$np[':']=$dif;
	$dowhere = true;
}


$diffs = getDifficulties();
$db = getDBConnection();

if(size == 0)
{

	$sql = "";
	for($i=2; $i<=$MAX_PUZZLE_SIZE; $i++)
	{
		if($i != 2)
		{
			$sql .= " UNION ";
		}
		$sql.= "SELECT * FROM puzzle$i$where LIMIT $start, $end";
	}
}
else
{
	$sql = "SELECT * FROM puzzle$size".$where." LIMIT $start, $end";
}
$stmt = $db->prepare($sql);
if(!$stmt->execute($np))
{
	die("SQL ERROR");
}

?>
<form style='margin-left:30%;margin-right:30%;'>

	<div class="form-group">
		<label for='size'>Puzzle type: </label>
		<select class="form-control" name='size'>
			<?php
					echo '<option value=0'.(($size == 0)?'selected="selected"':'').'>All puzzles</option>';
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
						else
						{
							$t = $s * $s;
							echo $t."X".$t." puzzle";
						}
						echo "</option>";
					}
			?>
		</select>
	</div>

	<input type='hidden' name='start' value='<?php echo $start;?>'/>

	<div class="form-group">
		<label for='count'>Number of results per page: </label>
		<input class='form-control' type='number' name='count' value='<?php echo $count;?>'/>
	</div>

	<div class="form-group">
		<label for='count'>Current page: </label>
		<input class='form-control' type='number' name='page' value='<?php echo $page;?>'/>
	</div>

	<div class="form-group">
		<label for='count'>Difficulty: </label>
		<select class="form-control" name='dif'>
			<?php
				for($i=0;$i<=sizeof($diffs);$i++)
				{
						echo "<option value=$i";
						echo ($i == $dif)?" selected='selected'>":">";
						echo (($i == 0)?"All difficulties":$diffs[$i]);
						echo "</option>";
				}
			?>
		</select>
	</div>

	<div class="form-group">
		<button class='btn btn-primary'>Go</button>
	</div>

</form>
<hr>
<?php
$start = true;
while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	if($start)
	{
		$start = false;
	}
	else
	{
		echo '<hr>';
	}
	$puzzle_string=$row['payload'];
	$puzzle=parsePuzzle($puzzle_string);
	echo makePuzzleSelector($puzzle, $puzzle_string, $diffs[$row['difficulty_id']], $row['difficulty_id']);
}


include('inc/footer.html');
?>