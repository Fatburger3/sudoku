<!DOCTYPE html>

<?php
include('admin_session.php');
include('inc/layout.php');
include('inc/sudoku.php');
include('inc/header.html');
echo makeAdminNav("Tables", "admin_tables.php");

function displayForm($tables, $selected, $start, $end)
{
	$form="<form style='margin-left:30%;margin-right:30%;'>";
	$form.='<div class="form-group">';
	$form.="<label for='table'>Table: </label>";
	$form.="<select class='form-control' name='table' value='$selected'>";

	foreach($tables as $table)
	{
		$form.="<option value=$table ";
		if($table==$selected)$form.="selected='selected'";
		$form.=">$table</option>";
	}

	$form.="</select>";
	$form.='</div>';
	$form.='<div class="form-group">';
	$form.="<label for='start'>Starting at row: </label>";
	$form.="<input class='form-control' type='number' name='start' value='$start'/>";
	$form.='</div>';
	$form.='<div class="form-group">';
	$form.="<label for='end'>Ending at row: </label>";
	$form.="<input class='form-control' type='number' name='end' value='$end'/>";
	$form.='</div>';
	$form.='<div class="form-group">';
	$form.="<button class='btn btn-primary'>Go</button>";
	$form.='</div>';
	$form.="</form>";
	return $form;
}

function displayTable($table, $start, $end)
{

	$db = getDBConnection();

	// getCols($table) prevents SQL injection of $table,
	// if injection is attempted, die is called.
	$cols = getCols($table);

	$result="";

	$result.="<table align='center' style='padding:30px;overflow-x:scroll;width:100%;table-layout:fixed'>";
	$result.="<tr>";
	$i = 0;
	$f = 0;
	foreach($cols as $col)
	{
		$i ++;
		$result.="<th title='$col' style='white-space: nowrap; text-overflow:ellipsis; overflow: hidden; max-width:1px;'>".$col;
		$result.="</th>";
	}

	$result.="<th>Actions</th>";
	$result.="</tr>";
	if($f == 1)
	{
		$result.="<td colspan='3'></td>";
	}

	$stmt = $db->prepare("SELECT * FROM $table LIMIT :s, :e;");
	$np = array();
	$np[':s'] = $start;
	$np[':e'] = $end;
	$stmt->execute($np);


	// For adding rows
	$result.="<tr>";
	$result.="<form method='post' action='admin_modifytable.php'>";
	$i = 0;
	foreach($cols as $col)
	{
		$result.="<td title='$col'>";

		$result.="<input type='text' name='$col' value='' style='width:100%;'/>";

		$result.="</td>";
	}
	$result.="<td>";
	$result.="<input type='hidden' name='table' value='$table'/>";
	$result.="<input type='hidden' name='start' value='$start'/>";
	$result.="<input type='hidden' name='end' value='$end'/>";
	$result.="<input type='submit' name='addrow' value='Add row' style='width:100%;'/>";
	$result.="</td>";
	$result.="</form>";
	$result.="</tr>";

	// Now print all the modifiable rows
	while($row = $stmt->fetch())
	{
		$result.="<form method='post' action='admin_modifytable.php'>";
		$i = 0;
		foreach($cols as $col)
		{
			$result.="<td title='$col'>";
			if($col != 'pass')
			{
				$result.="<input type='text' name='$col' value='".$row[$col]."' style='width:100%;'/>";
				$result.="<input type='hidden' name='original_$col' value='".$row[$col]."'/>";
			}
			else if($col == 'payload')
			{
				$result.=;
			}
			else
			{
				$result.="<input type='text' name='$col' value='' style='width:100%;'/>";
				$result.="<input type='hidden' name='original_$col' value='".$row[$col]."'/>";
			}
			$result.="</td>";
		}
		$result.="<td>";
		$result.="<input type='hidden' name='table' value='$table'/>";

		//saving this stuff for after modifytable.php executes
		$result.="<input type='hidden' name='start' value='$start'/>";
		$result.="<input type='hidden' name='end' value='$end'/>";

		$result.="<input type='submit' name='delete' value='Delete' style='width:50%;'/>";
		$result.="<input type='submit' name='update' value='Update' style='width:50%;'/>";
		$result.="</td>";
		$result.="</form>";
		$result.="</tr>";
	}
	$result.="</table>";
	return $result;
}

//Default table to display is none
$table='';
$start=0;
$end=100;
$message='';
if(isset($_GET['table']))
{
	$table=$_GET['table'];
}
if(isset($_GET['start']))
{
	$start=$_GET['start'];
}
if(isset($_GET['end']))
{
	$end=$_GET['end'];
}
if(isset($_GET['message']))
{
	$message=$_GET['message'];
}

$tables=getTables();
$body.=$message;
$body.=displayForm($tables, $table, $start, $end);
if($table!='')
{
	$body.="$table:<br/>";
	$body.=displayTable($table, $start, $end);
}

echo $body;
?>