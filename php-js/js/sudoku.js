
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

// When the "Random" Radio buttons are changed
function randomChanged()
{
	if(document.getElementById('random_on').checked)
	{
		$('#select_puzzle').css('display','none');
		$('#choose_puzzle').val('Random Puzzle');
	}
	else
	{
		$('#select_puzzle').css('display','block');
		$('#choose_puzzle').val('Select Puzzle');
	}
}
			
// Get the index of these x, y coords
function indexOf(x, y)
{
    return (9 * y) + x;
}

// Get the index of these x, y, xb, yb coords
function indexOfBlock(xb, yb, x, y)
{
    return indexOf((3*xb)+x,(3*yb)+y);
}

// Gets the block # that this cartesian index(x or y) is in
function block(xy)
{
    return parseInt(xy / 3);
}

// Solve this puzzle array
function solvePuzzle(puzzle)
{
	// Find the first empty cell
	var i = 0;
	while(puzzle[i] !== 0)
	{
		i++;
		if(i === 81)
		{
			return puzzle; //found a solution
		}
	}
	
	// Get the positions on the grid for easy use
	var x = i % 9;
	var y = parseInt(i / 9);

	//Grab the blocks
	var xb = block(x);
	var yb = block(y);
	
	// Now try to fill the cell with a value
	// Accept values 1 thru 9
	var conflict = 0;
	for(var v = 1; v < 10; v++)
	{
		
		// Check row and column conflicts (both at same time)
		conflict = 0;
		for(var j = 0; j < 9; j++)
		{
			// X conflicts
			if(puzzle[indexOf(j, y)] === v)
			{
				conflict = 1;
				break;
			}
			// Y conflicts
			if(puzzle[indexOf(x, j)] === v)
			{
				conflict = 1;
				break;
			}
		}
		
		
		//Check the local block for conflicts
		for(var j = 0; j < 3; j++)
		{
			for(var k = 0; k < 3; k++)
			{
				if(puzzle[indexOf((3 * xb) + j,(3 * yb) + k)] === v)
				{
					conflict = 1;
					break;
				}
			}
		}
		if(conflict === 1) continue;
		
		// There are no conflicts, so we test-fill this cell with this value, then move on to the next cell
		puzzle[indexOf(x, y)] = v;
		var rec = solvePuzzle(puzzle);
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
function fillPuzzle(puzzle)
{
	for(var x = 0; x < 9; x++)
	{
		for(var y = 0; y < 9; y++)
		{
			$("#" + indexOf(x, y)).val(puzzle[indexOf(x, y)]);
		}   
	}
}

// Gets the puzzle from the form
function getPuzzle()
{
    var puzzle = new Array(9);
    for (var i = 0; i < 9; i++)
    {
        puzzle[i] = new Array(9);
    }
    
	for(var x = 0; x < 9; x++)
	{
		for(var y = 0; y < 9; y++)
		{
		    var v = $("#" + indexOf(x, y)).val();
		    var z = 0;
			puzzle[indexOf(x, y)] = parseInt(v);
		    for(var k = 0; k <= 9; k++)
		    {
		        if(puzzle[indexOf(x, y)] === k)
		        {
		            z = 1;
		            break;
		        }
		    }
		    if(z === 0)
		    {
			    puzzle[indexOf(x, y)] = 0;
		    }
		}
	}
	return puzzle;
}

// Get the puzzle from form elements, solve it, and fill the form with the solution
function solvePuzzleForm()
{
	var puzzle = getPuzzle();
	solvePuzzle(puzzle);
	fillPuzzle(puzzle);
}