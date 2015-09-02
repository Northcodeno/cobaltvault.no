<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require($_SERVER['DOCUMENT_ROOT'] . "/lib/project.php");

if(!isset($_GET['id']))
	exit();

$Project = new Project($_GET['id']);

?>


<ul class="list-group">
<?php
foreach($Project->files as $file)
{
	?>
	<li class="list-group-item">
	<?php echo $file->getGlyphicon() . ' ' .$file->filename; ?> <a href="/scripts/project.php?id=<?php echo $Project->id; ?>&a=delfile&file=<?php echo $file->id; ?>" class="btn btn-danger pull-right">Delete</a>
	</li>
	<?php
}
?>
</ul>