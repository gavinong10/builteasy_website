<?php 
/**
* Template Name: Faq
*/

get_header(); 
global $wpdb;

	?>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<style>

.co-help a[aria-expanded="true"]:before, .home-help a[aria-expanded="true"]:before {
    content: "-";
    font-size: 24px;
    position: absolute;
    left: -23px;
    line-height: 20px;
    top: -1px;
}
.panel-heading {
    padding: 0px 15px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
}
.container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
    display: block;
}
.col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}div {
    display: block;
}.panel {
    margin-left: 22px;
}
.panel-group .panel {
    margin-bottom: 0;
    border-radius: 4px;
}
.panel {
    background: transparent none repeat scroll 0 0;
    border: 0 none;
    box-shadow: none;
    margin-top: 0;
}
  margin-left:0;}
  .co-help .panel-title>a, .co-help .panel-body, .home-help .panel-title>a, .home-help .panel-body {
    font-size: 14px;
    letter-spacing: 0;
    color: #000;
}.co-help section a {
    position: relative;
    line-height: 20px;
}.co-help a[aria-expanded="false"]:before, .home-help a[aria-expanded="false"]:before {
    content: "+";
    font-size: 24px;
    position: absolute;
    left: -23px;
    top: -1px;
    line-height: 20px;
}
.help-basic h3 {
    border-bottom: solid 1px #000;
    margin-bottom: 50px;
}
.help-basic h3{
    color: #000;
    font-size: 18px;
    font-family: 'Roboto Condensed', Helvetica, Arial, Lucida, sans-serif;
    letter-spacing: 1px;
    font-weight: 400;
    text-transform: uppercase;
    text-decoration: none;
}
.help-basic h3 a {
    font-size: 12px;
    font-family: 'Roboto Condensed', Helvetica, Arial, Lucida, sans-serif;
    letter-spacing: 1px;
    font-weight: 400;
    text-transform: uppercase;
    text-decoration: none;
    float: right;
}
section.help-search.text-center {
    background: #F7FAFC !important;
    padding: 80px 0;
}
.nav>li>a {
    padding: 0px 0;
}
#live-search input#filter {
    padding: 25px 20px;
    border: solid 1px #000;
    color: #000;
    font-size: 18px;
    letter-spacing: 1px;
    border-radius: 0;
    background: #fff;
}
#live-search input#filter::-webkit-input-placeholder { 
 color: #000;
}
#live-search input#filter::-moz-placeholder { 
 color: #000;
}
#live-search input#filter:-ms-input-placeholder { 
  color: #000;
}
#live-search input#filter:-moz-placeholder { 
 color: #000;
}
.co-help .panel-title a {
    font-family: 'Open Sans', sans-serif;
    text-decoration: none;
}
.panel-group .panel-heading+.panel-collapse>.list-group, .panel-group .panel-heading+.panel-collapse>.panel-body {
    border-top: 1px solid #000;
    font-family: 'Open Sans', sans-serif;
    font-size: 13px;
    line-height: 22px;
    color: #000;
	text-align: justify;
}
.co-help section .panel-body a {
    text-transform: lowercase;
    width: 100%;
    float: right;
    text-align: right;
}
.searchresult {
    margin-top: 40px;
    font-family: 'Roboto Condensed', Helvetica, Arial, Lucida, sans-serif;
    font-size: 15px;
    font-weight: 400;
    margin: 0;
    width: 100%;
    float: left;
    /* background: #fff; */
    padding: 0% 0%;
    /* border-left: solid 1px; */
     border-bottom: solid 1px;
    /* border-right: solid 1px; */
}
.searchresult #new_div {
   
	
}
.searchresult #new_div a:hover{
	background:#ddd;
}
.searchresult #new_div a {
    font-weight: 600;
    text-decoration: none;
	margin: 0;
    padding: 10px 20px;
    float: left;
    width: 100%;
    background: #fff;
	border-left: solid 1px;
   
    border-right: solid 1px;
}

#live-search {
    margin-top: 30px;
    margin-left: 0;
    margin-bottom: 0;
}
.nav>li>a:focus, .nav>li>a:hover {
    background-color: transparent;
}
</style>
<section class="help-search text-center">
 <article class="container">
<div class="col-sm-12" >
	<form id="live-search" action="" class="styled form-group" method="post">
                <fieldset>
                    <input type="text" class="text-input form-control" id="filter" value="" size="100" placeholder="Search FAQ"/>
                     
                </fieldset>
            </form>
			<div class="panel-title searchresult" id ="new_div"></div>
            </div>
			 </article>
			</section>
<section class="co-help">

			
			<?php 
$terms = get_terms('ufaq-category');

foreach ( $terms as $term ) {
	?>
<section class="help-basic">
           	 <article class="container">
	            <h3><?php echo $term->name;?><a href="<?php echo site_url();?>/ufaq-category/<?php echo $term->slug;?>/" class="Viewmore">View All</a></h3>
				<?php
				$term_relation  = $wpdb->get_results("SELECT * FROM wp_term_relationships WHERE term_taxonomy_id ='$term->term_id'  ORDER BY object_id DESC limit 10");
				$loopNo = 1;
					?><div class="col-sm-6 panel-group" id="<?php echo $term->name;?>1"><?php
					$count = count($term_relation);
					
					
					$counter = 0;		
				foreach ( $term_relation as $object_id ) {
					$counter++;
	$array_id[] =  $object_id->object_id;
				$faq = $wpdb->get_row("SELECT * FROM wp_posts WHERE post_type ='ufaq' AND post_status='publish' AND ID ='".$object_id->object_id ."'");
				
			//echo $string = strtotime($faq->post_modified);
			
				?>
            	
                      <div class="panel">
                        <div class="panel-heading">
                          <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#<?php echo $term->name.''.$loopNo;?>" href="#1collapseOne<?php echo $term->slug .'_'.$object_id->object_id;?>" aria-expanded="false" class="collapsed">
							<?php echo $faq->post_title; ?>
                             </a>
                          </h4>
                        </div>
                        <div id="1collapseOne<?php echo $term->slug .'_'.$object_id->object_id;?>" class="panel-collapse collapse " aria-expanded="false">
                          <div class="panel-body">
							<?php 
							$string = strip_tags($faq->post_content);
							if (strlen($string) < 500) {
								// truncate string
								$stringCut = substr($string, 0, 500);

								// make sure it ends in a word so assassinate doesn't become ass...
								$string = substr($stringCut, 0, strrpos($stringCut, ' ')).'<a href='. $faq->guid .'>Read More</a>'; 
							}
							echo $string;
						//	echo $faq->post_content; ?>
                          </div>
                        </div>
                      </div>
                 
                   
                <?php
				
				if($counter%5==0){
					echo '</div><div class="col-sm-6 panel-group" id="'.$term->name.'2">';	
					$loopNo = 2;
				} 
				
				
				}
				?>   </div>    
					  
</article> 
           
            </section><?php }

 ?></section>
