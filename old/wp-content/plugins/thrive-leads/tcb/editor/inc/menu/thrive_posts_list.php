<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Thrive Posts List options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<li class="tve_btn_text tve_firstOnRow">
		<label class="tve_text">
			<?php echo __( "Number of posts", "thrive-cb" ) ?> <input type="text" class="tve_change" id="posts_list_no_posts" size="2"/>
		</label>
		&nbsp;
		<label class="tve_text">
			<?php echo __( "Show", "thrive-cb" ) ?>
			<select class="tve_change" id="posts_list_filter">
				<option value="recent"><?php echo __( "Recent posts", "thrive-cb" ) ?></option>
				<option value="popular"><?php echo __( "Popular posts", "thrive-cb" ) ?></option>
			</select>
		</label>
		&nbsp;
		<label class="tve_text">
			<?php echo __( "Category", "thrive-cb" ) ?>
			<select class="tve_change" id="posts_list_category">
				<?php foreach ( $posts_categories as $id => $name ) : ?>
					<option value="<?php echo $id ?>"><?php echo $name ?></option>
				<?php endforeach ?>
			</select>
		</label>
	</li>
	<li class="tve_btn_text">
		<label>
			<?php echo __( "Display thumbnails", "thrive-cb" ) ?> <input type="checkbox" class="tve_change" id="posts_list_thumbnails"/>
		</label>
	</li>
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
</ul>