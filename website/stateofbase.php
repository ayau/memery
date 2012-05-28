<?php 
	include_once "common/base.php";
	$pageTitle = "state of the base";
	include_once "common/header.php";
	include_once "inc/class.dbconnector.inc.php";
	$dbConnector = new db_connector($db);
?>
<div id="container">
	<br /><br />
	<table id='database_info'>
		<tr><td colspan=2><h3>Current Progress</h3></td></tr>
		<tr><td>Total memes crawled</td><td><?php echo $dbConnector->getMemesCrawled()?></td></tr>
		<tr><td>Total memes processed</td><td><?php echo $dbConnector->getMemesProcessed()?></td></tr>
		<tr style='height:30px'/>
		<tr><td>Unique memes found</td><td></td></tr>
		<tr><td>Unique meme templates</td><td></td></tr>
		<tr><td>Date last crawled</td><td></td></tr>
	</table>
</div>

<?php
	include_once "common/footer.php"
?>