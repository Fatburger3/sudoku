///AUTHOR: Carsen Yates
///2015:06:04
///DESCRIPTION: [HEADER] Class for the SudokuPuzzle object.
/*=========================
Vocabulary:
+X: Horizontal position.
+Y: Vertical position.
+Z: Value at position X, Y.
+Size: The length of the side of a puzzle, usually 9 for regular sudoku puzzles.
+Cell: A single square in the puzzle.
+Block: A square with the number of cells equal to size (A puzzle of size 9 will have 9 cells per block).
+Collection Types: BLOCK, ROW, and COL
/*=========================
Notes:
+The length of the grid array MUST be equal to size^2. So acceptable sizes are only square numbers (4, 9, 16).
+Printing style:
╔═══════╤═══════╤═══════╗
║ 0 0 0 │ 0 0 0 │ 0 0 0 ║
║ 0 0 0 │ 0 0 0 │ 0 0 0 ║
║ 0 0 0 │ 0 0 0 │ 0 0 0 ║
╟───────┼───────┼───────╢
║ 0 0 0 │ 0 0 0 │ 0 0 0 ║
║ 0 0 0 │ 0 0 0 │ 0 0 0 ║
║ 0 0 0 │ 0 0 0 │ 0 0 0 ║
╟───────┼───────┼───────╢
║ 0 0 0 │ 0 0 0 │ 0 0 0 ║
║ 0 0 0 │ 0 0 0 │ 0 0 0 ║
║ 0 0 0 │ 0 0 0 │ 0 0 0 ║
╚═══════╧═══════╧═══════╝
*/
const int BLOCK=0, ROW=1, COL=2;
class SudokuPuzzle
{
public:
	//Creates an empty puzzle.
	SudokuPuzzle(int size);
	//Creates a puzzle and fills it with values.
	SudokuPuzzle(int*grid, int size);
	//Copies a SudokuPuzzle.
	SudokuPuzzle(const SudokuPuzzle&copyFrom);
	//Prints the puzzle in a readable form.
	void print()const;
	//Prints the puzzle without automatically formatting for hexadoku.
	void printStandard()const;
	//Returns true if the puzzle is solved.
	bool solved()const;
	//Gets the value at x, y.
	int at(int x, int y)const;
	//Gets the value at x, y in block bx, by.
	int at(int bx, int by, int x, int y)const;
	//Checks if 'z' fits at x, y.
	bool fit(int x, int y, int z)const;
	//Gets the x, y position of the next cell
	bool next(int&x, int&y)const;
	//Finds the next empty position after x, y.
	bool nextEmpty(int&x, int&y)const;
	//Returns the length/width of the puzzle(for regular sudoku this is '9').
	int getSize()const;
	//Returns the length/width of a block in the current puzzle.
	int getBlockSize()const;
	//Returns true if the collection type contains the value z.
	//A row requires a y value, a col requires an x value, and a block requires x and y
	bool contains(int collectionType, int x, int y, int z)const;
	//Puts the value 'z' at x, y.
	void set(int x, int y, int z);
	//Returns a solved puzzle.
	SudokuPuzzle solve()const;
	//Returns a solved puzzle, with the number of guesses it took to solve.
	SudokuPuzzle solve(int&guesses)const;
private:
	int*grid;
	int size;
	int blockSize;
};
