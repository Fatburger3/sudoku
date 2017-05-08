<?php
include("sudoku.php");

// Echos a navBar
function makeNav($title, $current)
{
	$names=array("Play", "Search", "Admin");
	$pages=array("index.php", "search.php", "admin_index.php");

	$result = '
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
					$result .= '<li class="active">';
				}
				else
				{
					$result .= '<li>';
				}
				$result .= '<a href="'.$p.'">'.$n.'</a></li>';
			}
	$result .= '
			</ul>
		</div>
	</nav>
	<h1>'.$title.'</h1>';
}

// Echos the secondary navbar for the admin page
function makeAdminNav($title, $current)
{
	$result .= makeNav('Administration', 'admin_index.php');
	$names=array("Reports", "Tables", "Logout");
	$pages=array("admin_reports.php", "admin_tables.php", "admin_logout.php");

	$result .= '<div class="btn-group" role="group">';
		for($i=0;$i<count($pages);$i++)
		{
			$p=$pages[$i];
			$n=$names[$i];
			if($current===$p)
			{
				$result .= '<a class="btn btn-default disabled" href="'.$p.'" aria-disabled="true">'.$n.'</a></li>';
			}
			else
			{
				$result .= '<a class="btn btn-default" href="'.$p.'">'.$n.'</a></li>';
			}
		}
	$result .= '
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
function makePuzzle($puzzle)
{
	$s = getPuzzleSize($puzzle);

	$result = '<table align="center" class="puzzle">';
	for($yb = 0; $yb < $s; $yb++)
	{
		$result .= '<tr class="puzzle_block_row">';
		for($xb = 0; $xb < $s; $xb++)
		{
			$result .= '<td class="puzzle_block"><table>';
			for($y = 0; $y < $s; $y++)
			{
				$result .= '<tr class="puzzle_row">';
				for($x = 0; $x < $s; $x++)
				{
					$i = indexOfBlock($xb, $yb, $x, $y);
					$result .= '<td class="puzzle_cell">';
					//$result .= ($puzzle[$i] == 0?' ':$puzzle[$i]);
					$result .= ($puzzle[$i]);
					$result .= '</td>';
				}
				$result .= '</tr>';
			}
			$result .= '</table></td>';
		}
		$result .= '</tr>';
	}
	$result .= '</table>';
	return $result;
}

// Display the puzzle on the form
function makePuzzleForm($puzzle)
{
	$s = getPuzzleSize($puzzle);

	//$result .=  '<form class="puzzle_form">';
	$result = '<table align="center" class="puzzle">';
	$i = 0;
	for($yb = 0; $yb < $s; $yb++)
	{
		$result .= '<tr class="puzzle_block_row">';
		for($xb = 0; $xb < $s; $xb++)
		{
			$result .= '<td class="puzzle_block"><table>';
			for($y = 0; $y < $s; $y++)
			{
				$result .= '<tr class="puzzle_row">';
				for($x = 0; $x < $s; $x++)
				{
					$i = indexOfBlock($s, $xb, $yb, $x, $y);
					$v = $puzzle[$i];
					$result .= '<td class="puzzle_cell">';
					$result .= '<input class="puzzle_input_cell" type="text" size="1" name="'.$i.'" id="'.$i.'" ';
					if($v != 0)
					{
						$result .= 'value="'.$v.'"';
					}
					$result .= '/>';
					$result .= '</td>';
				}
				$result .= '</tr>';
			}
			$result .= '</table></td>';
		}
		$result .= '</tr>';
	}
	$result .= '</table><br/>';
	$result .= '<div class="btn-group" role="group" aria-label="Basic example">';
	$result .= '<button class="btn btn-success" onclick="solvePuzzleForm(); return false;">Solve</button>';
	$result .= '<button class="btn btn-warning" onclick="clearInputs(); return false;">Clear</button>';
	$result .= '</div>';
	//$result .=  '</form>';
	return $result;
}
?>