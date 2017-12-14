<?php ob_start();

	if(!isset($_REQUEST['action']) || $_REQUEST['action']=='delete'){
	
	require('projects_list.php');	

    //Create an instance of our package class...

    $projectList = new projectList();

    //Fetch, prepare, sort, and filter our data...

    $projectList->prepare_items();   
?>

<div class="wrap">

	<h2>PDF Management <a href="?page=<?php echo $_REQUEST['page']?>&action=add" class="add-new-h2">Add New</a></h2>

	<?php if(!empty($projectList->notify)) { ?>

		<div id="message" class="updated below-h2">

			<p><?php echo $projectList->notify; ?></p>

		</div>

		<?php } ?>
		
		<form  method="post" name="projects">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
		<?php $projectList->display(); ?>
		</form>
		
	</div>

<?php 
ob_flush(); }else if($_REQUEST['action']=='add' || $_REQUEST['action']=='edit' ){
	require('add_project.php');	
}
exit; ?>