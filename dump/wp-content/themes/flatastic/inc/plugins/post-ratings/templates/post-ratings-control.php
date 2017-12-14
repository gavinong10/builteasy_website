<?php
/*
 * @template  Post Ratings Control
 * @revised   May 14, 2012
 * @author    digitalnature, http://digitalnature.eu
 * @license   GPL, http://www.opensource.org/licenses/gpl-license
 */


/* This is the HTML template for the ratings control/info block.
	You can override it by creating your own template with the same name, inside your theme / child theme folder.

	The mark-up can be almost entirely changed, the only things required are the ".ratings" class for the wrapper div,
	and the "data-post" attribute on it, containing the ID of the current post.
	Note that you don't have to keep the filters below.

	The CSS can also be changed (and should be), see post_ratings.css ...

	Available variables inside this template:
	  $rating           - real, rating of the current post
	  $votes            - integer, number of votes the post has
	  $bayesian_rating  - real, weighted rating (score)
	  $max_rating       - integer, maximum possible rating

  */


// local variable, we will make this the title of the html block
$current_rating = apply_filters('post_ratings_current_rating', sprintf('%.2F / %d', $rating, $max_rating), $rating, $max_rating);

?>

<div class="ratings <?php if (is_singular()) print 'hreview-aggregate'; ?>" data-post="<?php the_ID(); ?>">

	<span class="rate-desc"><?php _e('Rate this item', MAD_BASE_TEXTDOMAIN); ?></span>

	<ul <?php if (!PostRatings()->currentUserCanRate()) print 'class="rated"'; ?> style="width:<?php print ($max_rating * 15); ?>px" title="<?php esc_attr($current_rating); ?>">

		<li class="rating" style="width:<?php print ($rating * 15); ?>px">
		  <span class="average">
			<?php print $current_rating; ?>
		  </span>
		  <span class="best">
			<?php print $max_rating; ?>
		  </span>
		</li>

		<?php //if (PostRatings()->currentUserCanRate()): // only display links if the user can rate the post ?>

			<?php for ($i = 1; $i <= $max_rating; $i++): ?>

				<?php $title = apply_filters('post_ratings_control_title', sprintf(__('Give %1$d out of %2$d stars', 'post-ratings'), $i, $max_rating), $i, $max_rating); ?>

				<li class="s<?php print $i; ?>">
					<a title="<?php esc_attr($title); ?>"></a>
				</li>

			<?php endfor; ?>

		<?php //endif; ?>
	</ul>

	<div class="meta">
		<span>(<?php printf(_n('%1$s vote', '%1$s votes', $votes, MAD_BASE_TEXTDOMAIN), sprintf('%d', $votes)); ?>)</span>
	</div>

</div>

      