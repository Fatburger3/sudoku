
// Clear all <input type="text"> tags
function clearInputs()
{
	var inputs = document.getElementsByTagName('input');
	for (var i = 0; i<inputs.length; i++)
	{
		if(inputs[i].type === 'text')
		{
			inputs[i].value = '';
		}
	}
}

// This returns the SIZE of the puzzle,
// defined as the number of CELLS on one edge
// of one BLOCK of the puzzle.
// A normal sudoku puzzle is 9x9,
// A 9x9 puzzle is SIZE 3.
// A 4x4 puzzle is SIZE 2.
// A 16x16 puzzle is SIZE 4.
function getPuzzleSize(puzzle)
{
	var l = puzzle.length;
	var t = Math.sqrt(l);
	if((t * t) != l)
	{
		console.log("error t = $t; l = $l; t * t = " + (t * t));
		return 0;
	}
	var s = Math.sqrt(t);
	if(s * s !== t)
	{
		console.log("invalid puzzle (1)");
		return 0;
	}
	return s;
}

// Get the index of these x, y coords
function indexOf(s, x, y)
{
	return (s * s * y) + x;
}

// Get the index of these x, y, xb, yb coords
function indexOfBlock(s, xb, yb, x, y)
{
	return indexOf(s, (s*xb)+x,(s*yb)+y);
}

// Gets the block # that this cartesian index(x or y) is in
function block(s, xy)
{
	return parseInt(xy / s);
}

// Solve this puzzle array
function solvePuzzle(puzzle)
{
	// This is the same code as getPuzzleSize(),
	// but I reuse $l and $t
	var l = puzzle.length;
	var t = Math.sqrt(l);
	if((t * t) != l)
	{
		return 0;
	}
	var s = Math.sqrt(t);
	if(s * s !== t)
	{
		console.log("invalid puzzle (1)");
		return 0;
	}
	return doSolvePuzzle(s, t, l, puzzle);
}

// Solve this puzzle array
function doSolvePuzzle(s, t, l, puzzle)
{
	// Find the first empty cell
	var i = 0;
	while(puzzle[i] !== 0)
	{
		i++;
		if(i === l)
		{
			return puzzle; //found a solution
		}
	}

	// Get the positions on the grid for easy use
	var x = i % t;
	var y = parseInt(i / t);

	//Grab the blocks
	var xb = block(s, x);
	var yb = block(s, y);

	// Now try to fill the cell with a value
	// Accept values 1 thru 9
	var conflict = 0;
	for(var v = 1; v <= t; v++)
	{

		// Check row and column conflicts (both at same time)
		conflict = 0;
		for(var j = 0; j < t; j++)
		{
			// X conflicts
			if(puzzle[indexOf(s, j, y)] === v)
			{
				conflict = 1;
				break;
			}
			// Y conflicts
			if(puzzle[indexOf(s, x, j)] === v)
			{
				conflict = 1;
				break;
			}
		}


		//Check the local block for conflicts
		for(var j = 0; j < s; j++)
		{
			for(var k = 0; k < s; k++)
			{
				if(puzzle[indexOf(s, (s * xb) + j,(s * yb) + k)] === v)
				{
					conflict = 1;
					break;
				}
			}
		}
		if(conflict === 1) continue;

		// There are no conflicts, so we test-fill this cell with this value, then move on to the next cell
		puzzle[indexOf(s, x, y)] = v;
		var rec = solvePuzzle(s, t, l, puzzle);
		if(rec != null) return rec;
		else
		{
			puzzle[i]=0;
			continue;
		}
	}
	// return null to let the previous function in the stack know that we could not solve
	return null;
}


// Fill the puzzle form with a new puzzle
function fillPuzzle(s, t, l, puzzle)
{
	for(var x = 0; x < t; x++)
	{
		for(var y = 0; y < t; y++)
		{
			$("#" + indexOf(s, x, y)).val(puzzle[indexOf(s, x, y)]);
		}
	}
}

// Gets the puzzle from the form
function getPuzzle()
{
	var s = parseInt($(".puzzle > .puzzle_size").html());
	console.log(s);
	var t = s * s;
	console.log(t);
	var l = t * t;
	console.log(l);
	var puzzle = new Array(l);

	for(var x = 0; x < t; x++)
	{
		for(var y = 0; y < t; y++)
		{
			var v = $("#" + indexOf(s, x, y)).val();
			var z = 0;
			puzzle[indexOf(s, x, y)] = parseInt(v);
			for(var k = 0; k <= t; k++)
			{
				if(puzzle[indexOf(s, x, y)] === k)
				{
					z = 1;
					break;
				}
			}
			if(z === 0)
			{
				puzzle[indexOf(s, x, y)] = 0;
			}
		}
	}
	return puzzle;
}

// Get the puzzle from form elements, solve it, and fill the form with the solution
function solvePuzzleForm()
{
	var puzzle = getPuzzle();
	// This is the same code as getPuzzleSize(),
	// but I reuse $l and $t
	var l = puzzle.length;
	var t = Math.sqrt(l);
	if((t * t) != l)
	{
		console.log("invalid puzzle (0)");
		return 0;
	}
	var s = Math.sqrt(t);
	if(s * s !== t)
	{
		console.log("invalid puzzle (1)");
		return 0;
	}
	doSolvePuzzle(s, t, l, puzzle);
	fillPuzzle(s, t, l, puzzle);
}