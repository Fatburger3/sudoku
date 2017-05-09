<?php

// This returns the SIZE of the puzzle,
// defined as the number of CELLS on one edge
// of one BLOCK of the puzzle.
// A normal sudoku puzzle is 9x9,
// A 9x9 puzzle is SIZE 3.
// A 4x4 puzzle is SIZE 2.
// A 16x16 puzzle is SIZE 4.
function getPuzzleSize($puzzle)
{
	$l = sizeof($puzzle);
	$t = sqrt($l);
	if(($t * $t) != $l)
	{
		die("error t = $t; l = $l; t * t = ".($t * $t));
	}
	$s = sqrt($t);
	if($s * $s !== $t)
	{
		die("invalid puzzle (0)");
	}
	return $s;
}

function getEmptyPuzzle($size)
{
	$puzzle = array();
	$s = $size * $size;
	$s *= $s;

	for($i=0;$i<$s;$i++)
	{
		$puzzle []= 0;
	}
	return $puzzle;
}

// Converts a string (usually from db) to an array that represents a puzzle;
function parsePuzzle($puzzle_string)
{
	$puzzle = array();
	foreach(explode(',', $puzzle_string) as $c)
	{
		$puzzle []= (int)$c;
	}

	//this does error checking for us :)
	getPuzzleSize($puzzle);
	return $puzzle;
}

// Converts a puzzle to a string to be stored in DB
function puzzleToString($puzzle)
{
	$result = '';
	$init = 0;
	foreach($puzzle as $i)
	{
		if($init === 0)
		{
			$init = 1;
		}
		else
		{
			$result .= ',';
		}
		$result .= (string) $i;
	}
	return $result;
}

// Get the index of these x, y coords
function indexOf($s, $x, $y)
{
	return ($s * $s * $y) + $x;
}

// Get the index of these x, y, xb, yb coords
function indexOfBlock($s, $xb, $yb, $x, $y)
{
	return indexOf($s, ($s*$xb)+$x,($s*$yb)+$y);
}

// Gets the block # that this cartesian index(x or y) is in
function block($s, $xy)
{
	return (int) ($xy / $s);
}

// Solve this puzzle array
function solvePuzzle($puzzle)
{
	// This is the same code as getPuzzleSize(),
	// but I reuse $l and $t
	$l = len($puzzle);
	$t = sqrt($l);
	if($t * $t !== $l)
	{
		die("invalid puzzle");
	}
	$s = sqrt($t);
	if($s * $s !== $t)
	{
		die("invalid puzzle");
	}
	return doSolvePuzzle($s, $t, $l, $puzzle);
}


// Solve this puzzle array
function doSolvePuzzle($s, $t, $l, $puzzle)
{
	// Find the first empty cell

	$i = 0;
	while($puzzle[$i] != 0)
	{
		$i++;
		if($i == $l)
		{
			return $puzzle; //found a solution
		}
	}

	// Get the positions on the grid for easy use
	$x = $i % $t;
	$y = (int)($i / $t);

	//Grab the blocks
	$xb = block($s, $x);
	$yb = block($s, $y);

	// Now try to fill the cell with a value
	// Accept values 1 thru 9
	for($v = 1; $v <= $t; $v++)
	{

		// Check row and column conflicts (both at same time)
		$conflict = 0;
		for($j = 0; $j < $t; $j++)
		{
			// X conflicts
			if($puzzle[indexOf($s, $j, $y)] == $v)
			{
				$conflict = 1;
				break;
			}
			// Y conflicts
			if($puzzle[indexOf($s, $x, $j)] == $v)
			{
				$conflict = 1;
				break;
			}
		}


		//Check the local block for conflicts
		for($j = 0; $j < $s; $j++)
		{
			for($k = 0; $k < $s; $k++)
			{
				if($puzzle[indexOf($s, ($s * $xb) + $j,($s * $yb) + $k)] == $v)
				{
					$conflict = 1;
					break;
				}
			}
		}
		if($conflict == 1) continue;

		// There are no conflicts, so we test-fill this cell with this value, then move on to the next cell
		$puzzle[indexOf($s, $x, $y)] = $v;
		$rec = doSolvePuzzle($s, $t, $l, $puzzle);
		if($rec != null) return $rec;
		else
		{
			$puzzle[$i]=0;
			continue;
		}
	}
	// return null to let the previous function in the stack know that we could not solve
	return null;
}
?>