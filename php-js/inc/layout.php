<?php
include("sudoku.php");

function makeNav($title, $current)
{
	$names=array("Play", "Search", "Admin");
	$pages=array("index.php", "search.php", "admin_index.php");

	echo '
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<ul class="nav navbar-nav">
	';
			for($i=0;$i<count($pages);$i++)
			{
				$p=$pages[$i];
				$n=$names[$i];
				if($current===$p)
				{
					echo '<li class="active">';
				}
				else
				{
					echo '<li>';
				}
				echo '<a href="'.$p.'">'.$n.'</a></li>';
			}
	echo '
			</ul>
		</div>
	</nav>
	<h1>'.$title.'</h1>';
}

function makeAdminNav($title, $current)
{
	makeNav('Administration', 'admin_index.php');
	$names=array("Reports", "Tables", "Logout");
	$pages=array("admin_reports.php", "admin_tables.php", "admin_logout.php");

	echo '<div class="btn-group" role="group">';
		for($i=0;$i<count($pages);$i++)
		{
			$p=$pages[$i];
			$n=$names[$i];
			if($current===$p)
			{
				echo '<a class="btn btn-default disabled" href="'.$p.'" aria-disabled="true">'.$n.'</a></li>';
			}
			else
			{
				echo '<a class="btn btn-default" href="'.$p.'">'.$n.'</a></li>';
			}
		}
	echo '
	</div>
	<h3>'.$title.'</h3>';
}

//A magical function that makes a set of radio buttons
//Because, you know, a set of radio buttons is behaviorally identical to a Dropdown
function makeRadios($title, $name, $value, $names, $values, $onclick)
{
	$result = '';
	$result.='<label><strong>'.$title.':</strong></label><br/>';
	for($i=0;$i<count($values);$i++)
	{
		$n = $names[$i];
		$v = $values[$i];
		$c = ($v == $value?'checked':'');
		$result.='<input id="radio_'.$v.'" onclick="'.$onclick.'" class="radio" type="radio" name="'.$name.'" value="'.$v.'"'.$c.'>'.$n.'</input>';
	}
	$result.='<br/>';
	return $result;
}

//A magical function that makes a dropdown
//Because, you know, a set of radio buttons is behaviorally identical to a Dropdown
function makeDropdown($title, $name, $value, $names, $values, $onclick)
{
	$result = '';
	$result.='<label><strong>'.$title.':</strong></label><select id="select_'.$name.'" name="'.$name.'" value="'.$value.'">';
	for($i=0;$i<count($values);$i++)
	{
		$n = $names[$i];
		$v = $values[$i];
		$result.='<option value="'.$v.'">'.$n.'</option>';
	}
	$result.='</select><br/>';
	return $result;
}

// Displays a puzzle object in a cute little grid
// This version is not editable
function displayPuzzle($puzzle)
{
	$s = getPuzzleSize($puzzle);

	echo '<table align="center" class="puzzle">';
	for($yb = 0; $yb < $s; $yb++)
	{
		echo '<tr class="puzzle_block_row">';
		for($xb = 0; $xb < $s; $xb++)
		{
			echo '<td class="puzzle_block"><table>';
			for($y = 0; $y < $s; $y++)
			{
				echo '<tr class="puzzle_row">';
				for($x = 0; $x < $s; $x++)
				{
					$i = indexOfBlock($xb, $yb, $x, $y);
					echo '<td class="puzzle_cell">';
					//echo ($puzzle[$i] == 0?' ':$puzzle[$i]);
					echo ($puzzle[$i]);
					echo '</td>';
				}
				echo '</tr>';
			}
			echo '</table></td>';
		}
		echo '</tr>';
	}
	echo '</table>';
}

// Display the puzzle on the form
function displayPuzzleForm($puzzle)
{
	$s = getPuzzleSize($puzzle);

	//echo '<form class="puzzle_form">';
	echo '<table align="center" class="puzzle">';
	$i = 0;
	for($yb = 0; $yb < $s; $yb++)
	{
		echo '<tr class="puzzle_block_row">';
		for($xb = 0; $xb < $s; $xb++)
		{
			echo '<td class="puzzle_block"><table>';
			for($y = 0; $y < $s; $y++)
			{
				echo '<tr class="puzzle_row">';
				for($x = 0; $x < $s; $x++)
				{
					$i = indexOfBlock($s, $xb, $yb, $x, $y);
					$v = $puzzle[$i];
					echo '<td class="puzzle_cell">';
					echo '<input class="puzzle_input_cell" type="text" size="1" name="'.$i.'" id="'.$i.'" ';
					if($v != 0)
					{
						echo 'value="'.$v.'"';
					}
					echo '/>';
					echo '</td>';
				}
				echo '</tr>';
			}
			echo '</table></td>';
		}
		echo '</tr>';
	}
	echo '</table><br/>';
	echo '<div class="btn-group" role="group" aria-label="Basic example">';
	echo '<button class="btn btn-success" onclick="solvePuzzleForm(); return false;">Solve</button>';
	echo '<button class="btn btn-warning" onclick="clearInputs(); return false;">Clear</button>';
	echo '</div>';
	//echo '</form>';
}
?>