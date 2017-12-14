<?php
if(trim($_REQUEST['action'])=='delete'){
	include_once("inc/ProjectManager_func.php");
    $obj= new ProjectManager_func();
	$pdf_id= $_REQUEST['id'];

	$deleted=$obj->deletePdfById($pdf_id);
	
	if($deleted){
		echo "<script>location.href='".site_url()."/wp-admin/admin.php?page=pdfs';</script>";
	}
}

if(!isset($_REQUEST['action']) || $_REQUEST['action']=='delete'){
	
	require('pdfs_list.php');	
    //Create an instance of our package class...
    $projectList = new projectList();
    //Fetch, prepare, sort, and filter our data...
    $projectList->prepare_items();   

?>

<div class="wrap">

	<h2>PDF Management <a href="?page=add_pdfs" class="add-new-h2">Add New</a></h2>
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

}else if($_REQUEST['action']=='add'){

	require('add_pdf.php');	

}
