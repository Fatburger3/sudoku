<!DOCTYPE html>

<?php
include('admin_session.php');
include('inc/layout.php');
include('inc/header.html');
echo makeAdminNav("", "");

?>
<label>
	Welcome to the admin page, from here you can view reports, but you can also edit any of the tables that already exist in the database, so be cautious.  As as admin you can select, insert, update and delete from any prexisting table.
</label>
<?php include('inc/footer.html')?>