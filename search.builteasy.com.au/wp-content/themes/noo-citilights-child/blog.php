<?php 
/*
* Template Name: Blog
* Author : Alok
*/

?>

<?php get_header() ?>

<div class="container-boxed  max offset">
<div class="row" style="min-height: 450px;">
<div class="col-md-8">
<?php $args = array(
	'posts_per_page'   => 5,
	'offset'           => 0,
	'category'         => '',
	'category_name'    => '',
	'orderby'          => 'date',
	'order'            => 'DESC',
	'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',
	'post_type'        => 'post',
	'post_mime_type'   => '',
	'post_parent'      => '',
	'author'	   => '',
	'post_status'      => 'publish',
	'suppress_filters' => true 
);
$posts_array = get_posts( $args ); 

//echo "<pre>";
//print_r($posts_array);
?>


<?php foreach($posts_array as $post) : setup_postdata($post); ?>
<div class="content-wrap" style="margin-bottom:30px;">
  <header class="content-header">
   <h1 class="content-title"><a href="<?php the_permalink(); ?>"><?php the_title() ?> </a></h1>
							
   <p class="content-meta">
     <i class="nooicon-file-image-o"></i>
     
     <span>Posted on<time class="entry-date"> <?php echo date('M-d-Y',strtotime($post->post_date)) ?></time></span>
     <span style="width:50%">by <a href="<?php echo get_site_url(); ?>/author/<?php echo the_author_meta( 'user_nicename' , $post->post_author); ?>/" title="Posts by <?php echo the_author_meta( 'user_nicename' , $post->post_author); ?>" rel="author"><?php echo the_author_meta( 'user_nicename' , $post->post_author); ?> </a></span>
			
   </p>
  </header>

  <div class="content">
    <p><?php echo substr($post->post_content,0,300); ?> <a href="<?php the_permalink(); ?>">......Read More </a></p>
 </div>
</div>

<?php endforeach; ?>

</div>

<div class"col-md-4">
  <?php get_sidebar(); ?>
</div>

</div>
</div>

<?php get_footer() ?>