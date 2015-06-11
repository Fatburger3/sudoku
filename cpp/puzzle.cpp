///AUTHOR: Carsen Yates
///2015:06:11
///DESCRIPTION: Basic implementation for the SudokuPuzzle object.
#include<iostream>
#include<string>
#include<iomanip>
#include<math.h>

#include"sudoku.h"
using namespace std;
SudokuPuzzle::SudokuPuzzle(int initialSize)
{
	blockSize=sqrt(size=initialSize);
	int i=size*size;
	grid=new int[i];
	for(--i;i>=0;i--)
		grid[i]=0;
}
SudokuPuzzle::SudokuPuzzle(int*initialGrid, int initialSize)
{
	blockSize=sqrt(size=initialSize);
	grid=initialGrid;
}
SudokuPuzzle::SudokuPuzzle(const SudokuPuzzle&copyFrom)
{
	size=copyFrom.size;
	blockSize=copyFrom.blockSize;
	grid=copyFrom.grid;
}
void SudokuPuzzle::print()const
{
	if(size!=16)
	{
		printStandard();
		return;
	}
	const int charSize=1;

	cout              <<"╔═════════╤═════════╤═════════╤═════════╗\n";
	const string rowSep="╟─────────┼─────────┼─────────┼─────────╢\n";


	for(int by=0;by<4;by++)
	{
		if(by!=0)cout<<rowSep;
		for(int ay=0;ay<4;ay++)
		{
			cout<<"║";
			for(int bx=0;bx<4;bx++)
			{
				if(bx!=0)cout<<" │";
				for(int ax=0;ax<4;ax++)
				{
					cout<<" ";
					int z=at(bx, by, ax, ay);
					char c=(z==0?'-':(z>9?'A'+z-10:'0'+z-1));
					cout<<c;
				}
			}
			cout<<" ║\n";
		}
	}
	cout<<"╚═════════╧═════════╧═════════╧═════════╝\n";
}
void SudokuPuzzle::printStandard()const
{
	int charSize=to_string(size).length();

	cout        <<"╔";
	string rowSep="╟",
	       bottom="╚";
	for(int b=0;b<blockSize;b++)
	{
		for(int a=(blockSize*(charSize+1));a>=0;a--)
		{
			cout        <<"═";
			rowSep=rowSep+"─";
			bottom=bottom+"═";
		}
		if(b!=blockSize-1)
		{
			cout        <<"╤";
			rowSep=rowSep+"┼";
			bottom=bottom+"╧";
		}
	}
	cout        <<"╗\n";
	rowSep=rowSep+"╢\n";
	bottom=bottom+"╝\n";


	for(int by=0;by<blockSize;by++)
	{
		if(by!=0)cout<<rowSep;
		for(int ay=0;ay<blockSize;ay++)
		{
			cout<<"║";
			for(int bx=0;bx<blockSize;bx++)
			{
				if(bx!=0)cout<<" │";
				for(int ax=0;ax<blockSize;ax++)
				{
					cout<<" ";
					int z=at(bx, by, ax, ay);
					cout<<setw(charSize);
					cout<<(z==0?"-":to_string(z));
				}
			}
			cout<<" ║\n";
		}
	}
	cout<<bottom;
}
bool SudokuPuzzle::solved()const
{
	int x=0, y=0;
	while(next(x,y))if(at(x,y)==0)return false;
	return true;
}
int SudokuPuzzle::at(int x, int y)const
{
	return grid[(size*y)+x];
}
int SudokuPuzzle::at(int bx, int by, int x, int y)const
{
	return at((bx*blockSize)+x,(by*blockSize)+y);
}
bool SudokuPuzzle::fit(int x, int y, int z)const
{
	if(contains(BLOCK, x, y, z))return false;
	if(contains(ROW, x, y, z))return false;
	if(contains(COL, x, y, z))return false;
	return true;
}
bool SudokuPuzzle::next(int&x, int&y)const
{
	if(++x==size)
	{
		x=0;
		if(++y==size)
		{
			y=0;
			return false;
		}
	}
	return true;
}
bool SudokuPuzzle::nextEmpty(int&x, int&y)const
{
	while(at(x,y)!=0)if(!next(x,y))
	{
		x=y=0;
		return false;
	}
	return true;
}
int SudokuPuzzle::getSize()const
{
	return size;
}
int SudokuPuzzle::getBlockSize()const
{
	return blockSize;
}
bool SudokuPuzzle::contains(int type, int x, int y, int z)const
{
	switch(type)
	{
	case ROW:case COL:
		for(int a=0;a<size;a++)
			if((type==ROW?at(a,y):at(x,a))==z)return true;
		return false;
	default:
		x=x-(x%blockSize);
		y=y-(y%blockSize);
		for(int iy=0;iy<blockSize;iy++)
			for(int ix=0;ix<blockSize;ix++)
				if(at(x+ix,y+iy)==z)return true;
		return false;
	}
}
void SudokuPuzzle::set(int x, int y, int z)
{
	grid[(size*y)+x]=z;
}
