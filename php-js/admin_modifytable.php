<?php
	include('inc/layout.php');
	include('admin_session.php');

	if($_SERVER['REQUEST_METHOD'] === 'POST' &&
		isset($_POST) &&
		isset($_POST['table']) &&
		isset($_POST['start']) &&
		isset($_POST['end'])
	){

		$puzzle = array();
		$i = 0;
		while(isset($_POST[(string)$i]))
		{
			$puzzle []= $_POST[(string)$i];
			$i++;
		}

		$db = getDBConnection();
		$table = $_POST['table'];
		$start = $_POST['start'];
		$end = $_POST['end'];
		$cols = getCols($table);
		//TODO PREVENT INJECTION OF TABLE
		if(isset($_POST['delete']))
		{
			// DELETE from table
			$q = "DELETE FROM $table WHERE ";
			$x = 0;
			foreach($cols as $col)
			{
				if($x == 0)
				{
					$x = 1;
				}
				else
				{
					$q.=" AND ";
				}
				$q.="$col=:$col";
			}
			$stmt = $db->prepare($q);
			$np=array();
			foreach($cols as $col)
			{
				if($col == 'payload')
				{
					$np[":$col"] = puzzleToString($puzzle);
				}
				else
				{
					$np[":$col"]=$_POST['original_'.$col];
				}
			}
			$stmt->execute($np);
			$message = "Rows deleted: ";
			$message.= $stmt->rowCount();
			header("Location: admin_tables.php?table=$table&start=$start&end=$end&message=$message");
		}
		else if(isset($_POST['update']))
		{
			// UPDATE table
			$q = "UPDATE $table SET ";
			$x = 0;
			foreach($cols as $col)
			{
				if($x == 0)
				{
					$x = 1;
				}
				else
				{
					$q.=", ";
				}
				$q.="$col=".($col=='pass'?("SHA1(:$col)"):(":$col"));
			}
			$q.=" WHERE ";
			$x = 0;
			foreach($cols as $col)
			{
				if($x == 0)
				{
					$x = 1;
				}
				else
				{
					$q.=" AND ";
				}
				$q.="$col=".":original_$col";
			}
			$np=array();
			foreach($cols as $col)
			{
				$np[":original_$col"]=$_POST['original_'.$col];
				if($col == 'payload')
				{
					$np[":$col"]=puzzleToString($puzzle);
				}
				else
				{
					$np[":$col"]=$_POST[$col];
				}
			}
			$stmt = $db->prepare($q);
			echo $q;
			$stmt->execute($np);
			$message = "Table updated";
			header("Location: admin_tables.php?table=$table&start=$start&end=$end&message=$message");
		}
		else if(isset($_POST['addrow']))
		{
			// UPDATE table
			$q = "INSERT INTO $table (";
			$x = 0;
			foreach($cols as $col)
			{
				if($x == 0)
				{
					$x = 1;
				}
				else
				{
					$q.=", ";
				}
				$q.="$col";
			}
			$q.=") VALUES (";
			$x = 0;
			foreach($cols as $col)
			{
				if($x == 0)
				{
					$x = 1;
				}
				else
				{
					$q.=" , ";
				}

				$q.=($col=='pass'?("SHA1(:$col)"):(":$col"));
			}
			$np=array();
			foreach($cols as $col)
			{
				if($col == 'payload')
				{
					$np[":$col"]=puzzleToString($puzzle);
				}
				else
				{
					if(!isset($_POST[$col]))
					{
						$message = "<div class='error'>ERROR: $col was empty</div>";
						header("Location: admin_tables.php?table=$table&start=$start&end=$end&message=$message");
					}
					$np[":$col"]=$_POST[$col];
				}
			}
			$q.=");";
			$stmt = $db->prepare($q);
			$stmt->execute($np);
			$message = "New row added to $table";
			header("Location: admin_tables.php?table=$table&start=$start&end=$end&message=$message");
		}
		else if(isset($_POST['open']) && isset($_POST['puzzle']) && isset($_POST['size']))
		{
			$puzzle = $_POST['puzzle'];
			$size = $_POST['size'];
			header("Location: index.php?puzzle=$puzzle&size=$size");
		}
	}
	else
	{
		// YOU'VE COME TO THE WRONG PLACE, I'M KICKING YOU OUT
		header("Location: admin_tables.php");
	}

?>