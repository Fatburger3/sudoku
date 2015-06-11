///AUTHOR: Carsen Yates
///2015:06:04
///DESCRIPTION: Command line interface for solving Sudoku puzzles, using the SudokuPuzzle class

#include<iostream>
#include<math.h>

#include"sudoku.h"

using namespace std;
int main()
{
	char c;
	int size, x,y,z;
	cout<<"Enter a puzzle size: ";
	cin>>size;
	SudokuPuzzle puzzle(size);
/*
	puzzle.set(0,0,8);
	puzzle.set(2,1,3);
	puzzle.set(3,1,6);
	puzzle.set(1,2,7);
	puzzle.set(4,2,9);
	puzzle.set(6,2,2);
	puzzle.set(1,3,5);
	puzzle.set(5,3,7);
	puzzle.set(4,4,4);
	puzzle.set(5,4,5);
	puzzle.set(6,4,7);
	puzzle.set(3,5,1);
	puzzle.set(7,5,3);
	puzzle.set(2,6,1);
	puzzle.set(7,6,6);
	puzzle.set(8,6,8);
	puzzle.set(2,7,8);
	puzzle.set(3,7,5);
	puzzle.set(7,7,1);
	puzzle.set(1,8,9);
	puzzle.set(6,8,4);



	cout<<"Input:\n";
	puzzle.print();
	int guesses=0;
	puzzle.solve(guesses).print();
	cout<<"Guesses: "<<guesses<<endl;*/

	while(true)
	{
		system("reset");
		puzzle.print();
		cout<<"Enter v to input values, s to solve, c to clear a cell, and e to exit: ";
		cin>>c;
		switch(c)
		{
			case'v':
				cout<<"X: ";cin>>x;
				cout<<"Y: ";cin>>y;
				cout<<"Z: ";cin>>z;
				if(puzzle.fit(x,y,z))puzzle.set(x,y,z);
				else cout<<"Invalid";
				break;
			case'c':
				cout<<"X: ";cin>>x;
				cout<<"Y: ";cin>>y;
				puzzle.set(x,y,0);
				
			case's':
				puzzle=puzzle.solve();
				break;
			case'e':return 0;
		}
	}

	string in="start";
}
