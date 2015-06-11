///AUTHOR: Carsen Yates
///2015:06:11
///DESCRIPTION: Solving algorithm for the SudokuPuzzle object

#include<thread>
#include"sudoku.h"
using namespace std;
bool _solve(SudokuPuzzle&puzzle, int size, int&guesses, int x, int y)
{
	if(!puzzle.nextEmpty(x, y))return true;
	for(int z=1;z<=size;z++)
	{
		guesses++;
		if(puzzle.fit(x,y,z))
		{
			puzzle.set(x,y,z);
			if(_solve(puzzle,size,guesses,x,y))return true;
			puzzle.set(x,y,0);
		}
	}
	return false;
}
SudokuPuzzle SudokuPuzzle::solve()const
{
	int guesses=0;
	return solve(guesses);
}
SudokuPuzzle SudokuPuzzle::solve(int&guesses)const
{
	SudokuPuzzle p(grid,size);
	if(_solve(p,size,guesses,0,0)) return p;
	return SudokuPuzzle(grid,size);
}
