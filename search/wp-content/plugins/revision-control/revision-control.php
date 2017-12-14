<?php
/**
 * Plugin Name: Revision Control
 * Plugin URI: https://dd32.id.au/wordpress-plugins/revision-control/
 * Description: Allows finer control over the number of Revisions stored on a global & per-type/page basis.
 * Text Domain: revision-control
 * Author: Dion Hulse
 * Version: 2.3.2
 */

$GLOBALS['revision_control'] = new Plugin_Revision_Control( plugin_basename( __FILE__ ) );
class Plugin_Revision_Control {
	var $basename = '';
	var $folder = '';
	var $version = '2.3.2';

	var $define_failure = false;
	var $options = array( 'per-type' => array('post' => 'unlimited', 'page' => 'unlimited', 'all' => 'unlimited'), 'revision-range' => '2..5,10,20,50,100' );

	function __construct($plugin) {
		//Set the directory of the plugin:
		$this->basename = $plugin;
		$this->folder = dirname($plugin);

		// Load options - Must be done on inclusion as they're needed by plugins_loaded
		$this->load_options();

		add_action('plugins_loaded', array($this, 'define_WP_POST_REVISIONS'));

		if ( ! is_admin() )
			return;

		//Register general hooks.
		add_action('init', array($this, 'load_translations')); // Needs to be done before admin_menu.
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_init', array($this, 'admin_init'));
	}

	function load_translations() {
		//Load any translations.
		load_plugin_textdomain(	'revision-control' );
	}
	
	function admin_init() {
		// Register post/page hook:
		foreach ( array('load-post-new.php', 'load-post.php', 'load-page-new.php', 'load-page.php') as $page )
			add_action($page, array($this, 'meta_box'));

		wp_register_script('revision-control', plugins_url( $this->folder . '/revision-control.js' ), array('jquery', 'wp-ajax-response'), $this->version . time());
		wp_register_style('revision-control', plugins_url( $this->folder . '/revision-control.css' ), array(), $this->version);
		wp_localize_script('revision-control', 'RevisionControl', 
						   array(
							'deleterevisions' => __('Are you sure you wish to delete the selected Revisions?', 'revision-control'),
							'unlockrevision' => __('Warning: Unlocking this post will cause the latest revision to be published!\nContinue?', 'revision-control'),
							'selectbothradio' => __('Please select a Left and Right revision to compare', 'revision-control'),
							'revisioncompare' => __('Revision Compare', 'revision-control')
							) );
		
		// Add post handlers.
		add_action('admin_post_revision-control-delete', array('Plugin_Revision_Control_Ajax', 'delete_revisions'));
		add_action('admin_post_revision-control-options', array('Plugin_Revision_Control_Ajax', 'save_options'));
		add_action('admin_post_revision-control-revision-compare', array('Plugin_Revision_Control_UI', 'compare_revisions_iframe'));
		
		add_action('save_post', array($this, 'save_post'), 10, 2);
		
		// Version the terms.
		add_action('_wp_put_post_revision', array($this, 'version_terms') );
		//Delete the terms
		add_action('wp_delete_post_revision', array($this, 'delete_terms'), 10, 2 );

		// Version the postmeta
		add_action('_wp_put_post_revision', array($this, 'version_postmeta') );
		// Postmeta deletion is handled by core.
	}
	
	function admin_menu() {
		add_options_page( __('Revision Control', 'revision-control'), __('Revisions', 'revision-control'), 'manage_options', 'revision-control', array('Plugin_Revision_Control_UI', 'admin_page'));
	}
	
	function meta_box() {
		foreach ( get_post_types() as $type ) {
			if ( post_type_supports($type, 'revisions') ) {
				remove_meta_box('revisionsdiv', $type, 'normal');
				add_meta_box('revisionsdiv', __('Post Revisions'), array('Plugin_Revision_Control_UI', 'revisions_meta_box'), $type, 'normal');
			}
		}

		//enqueue that Stylin' script!
		wp_enqueue_script('revision-control');
		wp_enqueue_style('revision-control');

		add_thickbox();
	}
	
	function save_post($id, $post) {
		$new = isset($_POST['limit_revisions'])        ? stripslashes($_POST['limit_revisions'])             : false;
		$old = isset($_POST['limit_revisions_before']) ? stripslashes_deep($_POST['limit_revisions_before']) : false;

		$id = 'revision' == $post->post_type ? $post->post_parent : $post->ID;
		if ( false !== $new )
			$this->delete_old_revisions($id, $new);

		if ( false === $new || false === $old || $new == $old)
			return;

		update_metadata('post', $id, '_revision-control', $new, $old);
	}
	
	function define_WP_POST_REVISIONS() {
		if ( defined('WP_POST_REVISIONS') ) {
			$this->define_failure = true; // This.. Is defineing failure.. as true!
			return;
		}
		
		$current_post = $this->get_current_post();
		if ( !empty($_REQUEST['limit_revisions']) ) { //Handle it when updating a post.
			if ( ! $default = $this->option($current_post->post_type, 'per-type') )
				$default = $this->option('all', 'per-type');
			$post_specific = array(stripslashes($_REQUEST['limit_revisions']));
		} else if ( $current_post ) {
			// Good, we've got a post so can base it off the post_type
			if ( ! $default = $this->option($current_post->post_type, 'per-type') )
				$default = $this->option('all', 'per-type');
			// Check to see if those post has a custom Revisions value:
			$post_specific = get_metadata('post', $current_post->ID, '_revision-control', true);
			if ( '' == $post_specific )
				$post_specific = false;
			else if ( ! is_array($post_specific) )
				$post_specific = Plugin_Revision_Control_Compat::postmeta($post_specific, $current_post);
			
		} else {
			// Guess based on the current page.
			global $pagenow;
			if ( !empty($_REQUEST['post_type']) )
				$post_type = stripslashes($_REQUEST['post_type']);
			else if ( 'page.php' == $pagenow || 'page-new.php' == $pagenow)
				$post_type = 'page';
			else if ( 'post.php' == $pagenow || 'post-new.php' == $pagenow)
				$post_type = 'post';
			else
				$post_type = '';

			if ( empty($post_type) )
				return; //Not needed.

			if ( ! $default = $this->option($post_type, 'per-type') )
				$default = $this->option('all', 'per-type');

		}
		// Ok, Lets define it.
		$define_to = isset($post_specific[0]) && '' != $post_specific[0] ? $post_specific[0] : $default;
		switch ( $define_to ) {
			case 'unlimited':
				define('WP_POST_REVISIONS', true);
				break;
			case 'never':
				define('WP_POST_REVISIONS', 0);
				break;
			case 'defaults':
				define('WP_POST_REVISIONS', $default);
				break;
			default:
				if ( is_numeric($define_to) )
					define('WP_POST_REVISIONS', (int)$define_to);
				else
					define('WP_POST_REVISIONS', true); // All else fails, Its this.
				break;
		}
	}

	function delete_old_revisions($id, $new) {
		$items = get_posts( array('post_type' => 'revision', 'numberposts' => 1000, 'post_parent' => $id, 'post_status' => 'inherit', 'order' => 'ASC', 'orderby' => 'ID') );
		if ( 'defaults' == $new ) {
			$post = get_post($id);
			if ( false === $default = $this->option($post->post_type, 'per-type') )
				$default = $this->option('all', 'per-type');
			$new = $default;
		}
		if ( ! is_numeric($new) ) {
			switch ( $new ) {
				case 'unlimited':
					$keep = count($items);
					break;
				case 'never':
					$keep = 0;
					break;
			}
		} else {
			$keep = max( $new, 0 );
		}	

		while ( count($items) > $keep ) {
			$item = array_shift($items);
			wp_delete_post_revision($item->ID);
		}
	}

	function get_current_post() {
		foreach ( array( 'post_id', 'post_ID', 'post' ) as $field )
			if ( isset( $_REQUEST[ $field ] ) )
				return get_post(absint($_REQUEST[ $field ]));

		if ( isset($_REQUEST['revision']) )
			if ( $post = get_post( $id = absint($_REQUEST['revision']) ) )
				return get_post($post->post_parent);

		return false;
	}
	
	function version_terms($revision_id) {
		// Attach all the terms from the parent to the revision.
		if ( ! $rev = get_post($revision_id) )
			return;
		if ( ! $post = get_post($rev->post_parent) )
			return;

		// Only worry about taxonomies which are specifically linked.
		foreach ( get_object_taxonomies($post->post_type) as $taxonomy ) {
			$_terms = wp_get_object_terms($post->ID, $taxonomy);
			$terms = array();
			foreach ( $_terms as $t )
				$terms[] = (int)$t->term_id;
			if ( ! empty($terms) )
				wp_set_object_terms($revision_id, $terms, $taxonomy);
		}
	}

	function delete_terms($revision_id, $rev) {
		if ( ! $post = get_post($rev->post_parent) )
			return;

		// Delete the parent posts taxonomies from the revision.
		wp_delete_object_term_relationships($revision_id, get_object_taxonomies($post->post_type) );
	}

	function version_postmeta($revision_id) {
		// Attach all the terms from the parent to the revision.
		if ( ! $rev = get_post($revision_id) )
			return;
		if ( ! $post = get_post($rev->post_parent) )
			return;

		// Only worry about taxonomies which are specifically linked.

	}
	
	static function sort_revisions_by_time($a, $b) {
		return strtotime($a->post_modified_gmt) < strtotime($b->post_modified_gmt);
	}
	
	function load_options() {
		$original = $options = get_option('revision-control', array());
		$options = Plugin_Revision_Control_Compat::options($options); // Lets upgrade the options..
		if ( $options != $original ) // Update it if an upgrade has taken place.
			update_option('revision-control', $options);

		$this->options = array_merge($this->options, $options); // Some default options may be set here, unless the user modifies them
	}
	
	function option($key, $bucket = false, $default = false ) {
		if ( $bucket )
			return isset($this->options[$bucket][$key]) ? $this->options[$bucket][$key] : $default;			
		else
			return isset($this->options[$key]) ? $this->options[$key] : $default;
	}

	function set_option($key, $value, $bucket = false) { 
		if ( $bucket )
			$this->options[$bucket][$key] = $value;
		else
			$this->options[$key] = $value;
		update_option('revision-control', $this->options);
	}

	function get_revision_limit_select_items($current = false) { 
		$items = array(
						'defaults' => __('Default Revision Settings', 'revision-control'),
						'unlimited' => __('Unlimited number of Revisions', 'revision-control'),
						'never' => __('Do not store Revisions', 'revision-control')
					   );
		$values = $this->option('revision-range', '');
		$values = explode(',', $values);
		foreach ( $values as $val ) {
			$val = trim($val);
			if ( preg_match('|^(\d+)\.\.(\d+)$|', $val, $matches) ) {
				foreach ( range( (int)$matches[1], (int)$matches[2]) as $num )
					$items[ $num ] = sprintf( _n( 'Maximum %s Revision stored', 'Maximum %s Revisions stored', $num, 'revision-control' ), number_format_i18n($num) );
			} else if ( is_numeric($val) ) {
				$num = (int)$val;
				$items[ $num ] = sprintf( _n( 'Maximum %s Revision stored', 'Maximum %s Revisions stored', $num, 'revision-control' ), number_format_i18n($num) );
			}
		}

		if ( false != $current && is_numeric($current) && !isset($items[ $current ]) ) // Support for when the range changes and the global/per-post has changed since.
			$items[ $current ] = sprintf( _n( 'Maximum %s Revision stored', 'Maximum %s Revisions stored', $current, 'revision-control' ), number_format_i18n($current) );

		return $items;
	}
	
}

class Plugin_Revision_Control_Compat {
	static function postmeta($meta, $post) {
		if ( is_array($meta) )
			return $meta;

		if ( ! is_numeric($meta) ) {
			$_meta = array($meta);
		} else {
			$_meta = array( (int) $meta );
			if ( 1 === $_meta[0] )
				$_meta[0] = 'unlimited';
			else if ( 0 === $meta[0] )
				$_meta[0] = 'never';
		}
		if ( $_meta != $meta )
			update_metadata('post', $post->ID, '_revision-control', $_meta, $meta);
		
		return $_meta;
	}

	static function options($options) {
		$_options = $options;
		if ( ! is_array($options) ) { // Upgrade from 1.0 to 1.1
			$options = array( 'post' => $options, 'page' => $options );
		}
		if ( isset($options['post']) ) { // Upgrade from 1.1 to 2.0
			$options['per-type'] = array( 'post' => $options['post'], 'page' => $options['page'] );
			unset($options['post'], $options['page']);
			
			// The fun part, Move from (bool) & (int) to (string) and (int). Easier to seperate with is_numeric that way.
			foreach ( $options['per-type'] as $type => $value ) {
				if ( true === $value )
					$options['per-type'][$type] = 'unlimited';
				elseif ( 0 === $value )
					$options['per-type'][$type] = 'never';
				elseif ( is_numeric($value) && (int)$value > 0 )
					$options['per-type'][$type] = (int)$options['per-type'][$type];
				else
					$options['per-type'][$type] = 'unlimited';
			}
		}
		return $options;
	}
}

class Plugin_Revision_Control_Ajax {
	static function delete_revisions() {
		//Add nonce check
		check_admin_referer('revision-control-delete');
		
		if ( empty($_POST['revisions']) ) {
			$x = new WP_AJAX_Response();
			$x->add( array('data' => -1) );
			$x->send();
			return;
		}
		
		$revisions = stripslashes($_POST['revisions']);
		$revisions = explode(',', $revisions);
		$revisions = array_map('intval', $revisions);

		$deleted = array();

		foreach ( $revisions as $revision_id ) {
			$revision = get_post($revision_id);
			if ( wp_is_post_revision($revision) && !wp_is_post_autosave($revision) && current_user_can('delete_post', $revision->post_parent) )
				if ( wp_delete_post_revision($revision_id) )
					$deleted[] = $revision_id;
		}

		$x = new WP_AJAX_Response();
		$x->add( array('data' => 1, 'supplemental' => array('revisions' => implode(',', $deleted)) ) );
		$x->send();
	}

	static function save_options() {
		global $revision_control;
		check_Admin_referer('revision-control-options');

		$data = stripslashes_deep($_POST['options']);
		foreach ( $data as $option => $val ) {
			if ( is_string($val) ) // Option is the keyname
				 $revision_control->set_option($option, $val);
			elseif ( is_array($val) ) // Option is the bucket, key => val are the options in the group.
				foreach ( $val as $subkey => $subval ) 
					 $revision_control->set_option($subkey, $subval, $option);
		}
		wp_safe_redirect( add_query_arg('updated', 'true', wp_get_referer() ) );
	}
}

class Plugin_Revision_Control_UI {
	static function compare_revisions_iframe() {
		//add_action('admin_init', 'register_admin_colors', 1);

		set_current_screen('revision-edit');

		$left  = isset($_GET['left'])  ? absint($_GET['left'])  : false;
		$right = isset($_GET['right']) ? absint($_GET['right']) : false;

		if ( !$left_revision  = get_post( $left ) )
			return;
		if ( !$right_revision = get_post( $right ) )
			return;

		if ( !current_user_can( 'read_post', $left_revision->ID ) || !current_user_can( 'read_post', $right_revision->ID ) )
			return;

		// Don't allow reverse diffs?
		if ( strtotime($right_revision->post_modified_gmt) < strtotime($left_revision->post_modified_gmt) ) {
			//$redirect = add_query_arg( array( 'left' => $right, 'right' => $left ) );
			// Switch-a-roo
			$temp_revision = $left_revision;
			$left_revision = $right_revision;
			$right_revision = $temp_revision;
			unset($temp_revision);
		}

		global $post;

		if ( $left_revision->ID == $right_revision->post_parent ) // right is a revision of left
			$post = $left_revision;
		elseif ( $left_revision->post_parent == $right_revision->ID ) // left is a revision of right
			$post = $right_revision;
		elseif ( $left_revision->post_parent == $right_revision->post_parent ) // both are revisions of common parent
			$post = get_post( $left_revision->post_parent );
		else
			wp_die( __('Sorry, But you cant compare unrelated Revisions.', 'revision-control') ); // Don't diff two unrelated revisions


		if (
			// They're the same
			$left_revision->ID == $right_revision->ID
		||
			// Neither is a revision
			( !wp_get_post_revision( $left_revision->ID ) && !wp_get_post_revision( $right_revision->ID ) )
		)
			wp_die( __('Sorry, But you cant compare a Revision to itself.', 'revision-control') );

		$title = sprintf( __( 'Compare Revisions of &#8220;%1$s&#8221;', 'revision-control' ), get_the_title() );
	
		$left  = $left_revision->ID;
		$right = $right_revision->ID;

		$GLOBALS['hook_suffix'] = 'revision-control';
		wp_enqueue_style('revision-control');

		iframe_header();

		?>
		<div class="wrap">
		
		<h2 class="long-header center"><?php echo $title ?></h2>
		
		<table class="form-table ie-fixed">
			<col class="th" />
		<tr id="revision">
			<th scope="col" class="th-full">
				<?php printf( __('Older: %s', 'revision-control'), wp_post_revision_title($left_revision, false) ); ?>
				<span class="alignright"><?php printf( __('Newer: %s', 'revision-control'), wp_post_revision_title($right_revision, false) ); ?></span>
			</th>
		</tr>
		<?php
		
		$fields = _wp_post_revision_fields();
		
		foreach ( get_object_taxonomies($post->post_type) as $taxonomy ) {
			$t = get_taxonomy($taxonomy);
			$fields[$taxonomy] = $t->label;
			
			$left_terms = $right_terms = array();
			foreach ( wp_get_object_terms($left_revision->ID, $taxonomy ) as $term )
				$left_terms[] = $term->name;
			foreach ( wp_get_object_terms($right_revision->ID, $taxonomy ) as $term )
				$right_terms[] = $term->name;

			$left_revision->$taxonomy = ( empty($left_terms) ? '' : "* " ) . join("\n* ", $left_terms);
			$right_revision->$taxonomy = ( empty($right_terms) ? '' : "* " ) . join("\n* ", $right_terms);
		}
		
		$fields['postmeta'] = __('Post Meta', 'revision-control');
		$left_revision->postmeta = $right_revision->postmeta = array();
		foreach ( (array)has_meta($right_revision->ID) as $meta ) {
			if ( '_' == $meta['meta_key'][0] )
				continue;

	  		$right_revision->postmeta[] = $meta['meta_key'] . ': ' . $meta['meta_value'];
			$left_val = get_post_meta('post', $left_revision->ID, $meta['meta_key'], true);
			if ( !empty( $left_val ) )
				$left_revision->postmeta[] = $meta['meta_key'] . ': ' . $left_val;
		}

		$right_revision->postmeta = implode("\n", $right_revision->postmeta);
		$left_revision->postmeta = implode("\n", $left_revision->postmeta);

		$identical = true;
		foreach ( $fields as $field => $field_title ) :
			if ( !$content = wp_text_diff( $left_revision->$field, $right_revision->$field ) )
				continue; // There is no difference between left and right
			$identical = false;
			?>
			<tr>
				<th scope="row"><strong><?php echo esc_html( $field_title ); ?></strong></th>
			</tr>
			<tr id="revision-field-<?php echo $field; ?>">
				<td><div class="pre"><?php echo $content; ?></div></td>
			</tr>
			<?php
		endforeach;
		
		if ( $identical ) :
			?><tr><td><div class="updated"><p><?php _e( 'These Revisions are identical.', 'revision-control' ); ?></p></div></td></tr><?php
		endif;
		?>
		</table>
		<p><?php _e('<em>Please Note:</em> at present, Although Taxonomies <em>(Tags / Categories / Custom Taxonomies)</em> are stored with the revisions, Restoring a Revision will <strong>not</strong> restore the taxonomies at present.', 'revision-control'); ?></p>
		<br class="clear" />
		<?php
		iframe_footer();
	}

	static function revisions_meta_box( $post_id = 0 ) {
		global $revision_control;

		if ( empty($post_id) )
			$post_id = $GLOBALS['post_ID'];

		if ( !$post = get_post( $post_id ) )
			return;
	
		if ( !$revisions = wp_get_post_revisions( $post->ID ) )
			$revisions = array();
		if ( !empty($post) && !empty($post->ID) )
			$revisions = $revisions + array($post);

		foreach ( $revisions as $key => $revision ) {
			if ( !current_user_can( 'read_post', $revision->ID ) )
				unset($revisions[$key]);
		}
			
		usort($revisions, array('Plugin_Revision_Control', 'sort_revisions_by_time'));
	?>
	<noscript><div class="updated"><p><?php _e('<strong>Please Note</strong>: This module requires the use of Javascript.', 'revision-control') ?></p></div></noscript>
	<input type="hidden" id="revision-control-delete-nonce" value="<?php echo wp_create_nonce( 'revision-control-delete' ) ?>" />
	<table class="widefat post-revisions" id="post-revisions" cellspacing="0">
		<col class="check-column" />
		<col class="check-column hide-if-no-js" />
		<col />
		<col style="width: 15%" />
		<col style="width: 15" />
	<thead>
	<tr>
		<th scope="col" class="check-column delete-column" style="text-align:center; white-space:nowrap;"><input type='checkbox' name='checked[]' class='checklist' /><?php _e( 'Delete', 'revision-control' ); ?></th>
		<th scope="col" class="check-column hide-if-no-js" style="text-align:center; white-space:nowrap;"><?php _e( 'Compare', 'revision-control' ); ?></th>
		<th scope="col"><?php _e( 'Date Created', 'revision-control' ); ?></th>
		<th scope="col"><?php _e( 'Author', 'revision-control' ); ?></th>
		<th scope="col" class="action-links"><?php _e( 'Actions', 'revision-control' ); ?></th>
	</tr>
	</thead>
	<tbody>
	
	<?php
	$titlef = _x( '%1$s by %2$s', 'post revision 1:datetime, 2:name', 'revision-control' );

	$rows = '';
	$class = false;
	if ( 0 == $post->ID )
		$can_edit_post = true;
	else
		$can_edit_post = current_user_can( 'edit_post', $post->ID );

	if ( empty($revisions) ) {
		echo "<tr class='no-revisions'>\n";
		echo "\t<td style='text-align: center' colspan='5'>\n";
		$p_obj = get_post_type_object($post->post_type);
		$obj_name = $p_obj->label;
		printf(_x('Revisions are currently enabled for %s, However there are no current Autosaves or Revisions created.<br />They\'ll be listed here once you Save. Happy Writing!', '1: the Post_Type - Posts, Pages, etc. (plural always)', 'revision-control'), $obj_name);
		echo "</td>\n";
		echo "</tr>\n";	
	}
	
	foreach ( $revisions as $revision ) {
		$date = wp_post_revision_title( $revision, false );
		$name = get_the_author_meta( 'display_name', $revision->post_author );
		
		$revision_is_current = $post->ID == $revision->ID;

		$class = strpos($class, 'alternate') !== false ? '' : "alternate";
		
		$class .= ' revision-' . $revision->ID;
		
		if ( $revision_is_current )
			$class .= ' current-revision';
		
		$actions = array();
		if ( ! $revision_is_current && !wp_is_post_autosave($revision) && $can_edit_post ) {
			$actions[] = '<a href="' . wp_nonce_url( add_query_arg( array( 'revision' => $revision->ID, 'diff' => false, 'action' => 'restore' ), 'revision.php' ), "restore-post_{$revision->ID}" ) . '">' . __( 'Restore', 'revision-control' ) . '</a>';
		}

		$deletedisabled = ( $revision_is_current || wp_is_post_autosave($revision) || ! $can_edit_post ) ? 'disabled="disabled"' : '';
		$lefthidden = $revision == end($revisions) ? ' style="visibility: hidden" ' : '';
		$righthidden = $revision == $revisions[0] ? ' style="visibility: hidden" ' : '';

		echo "<tr class='$class' id='revision-row-$revision->ID'>\n";
		echo "\t<th style='white-space: nowrap' scope='row' class='check-column hide-if-no-js'>
					<span class='delete'>
						<input type='checkbox' name='checked[]' class='checklist toggle-type' value='$revision->ID' $deletedisabled />
					</span>
				</th>
				<th style='white-space: nowrap' scope='row' class='check-column'>
					<span class='compare'>
						<input type='radio' name='left' class='left toggle-type' value='$revision->ID' $lefthidden />
						<input type='radio' name='right' class='right toggle-type' value='$revision->ID' $righthidden />
					</span>
				</th>\n";
		echo "\t<td>$date</td>\n";
		echo "\t<td>$name</td>\n";
		echo "\t<td class='action-links'>" . implode(' | ', $actions) . "</td>\n";
		echo "</tr>\n";
	}
	?>
	
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5">
				<input type="button" class="button-secondary" value="<?php esc_attr_e('Delete', 'revision-control') ?>" id="revisions-delete" />
				<span class="hide-if-no-js">
				<input type="button" class="button-secondary" value="<?php esc_attr_e('Compare', 'revision-control') ?>" id="revisions-compare" />
				</span>
				<span class="alignright">
					<?php if ( $revision_control->define_failure ) {
						_e('<strong>Error:</strong> <code>WP_POST_REVISIONS</code> is defined in your <code>wp-config.php</code>. <em>Revision Control</em> cannot operate.', 'revision-control');
					} else {
						$_current = $post_specific = get_metadata('post', $post->ID, '_revision-control', true);
						if ( '' == $post_specific )
							$post_specific = array('defaults');
						else if ( ! is_array($post_specific) )
							$post_specific = Plugin_Revision_Control_Compat::postmeta($post_specific, $post);
						$post_specific = $post_specific[0];
						$_current_display = $post_specific;
						if ( 'defaults' == $_current_display )
							$_current_display = $revision_control->option($post->post_type, 'per-type');
					?>
					<label for="limit-revisions"><strong><em><?php _e('Revision Control', 'revision-control') ?>:</em></strong>
					<?php
					if ( is_numeric($_current_display) )
						printf( _n( 'Currently storing a maximum of %s Revision', 'Currently storing a maximum of %s Revisions', $_current_display, 'revision-control' ), number_format_i18n($_current_display) );
					elseif ( 'unlimited' == $_current_display )
						_e('Currently storing an Unlimited number of Revisions', 'revision-control');
					elseif ( 'never' == $_current_display )
						_e('Not storing any Revisions', 'revision-control');
						?>
					<select name="limit_revisions" id="limit-revisions">
						<?php
						foreach ( $revision_control->get_revision_limit_select_items($post_specific) as $val => $text ) {
							$selected = $post_specific == $val ? 'selected="selected"' : '';
							echo "\t\t\t\t\t\t<option value='$val' $selected>$text</option>\n";
						}
						?></select>
						<input type="hidden" name="limit_revisions_before<?php if ( is_array($_current) ) echo '[]' ?>" value="<?php echo esc_attr( is_array($_current) ? $_current[0] : $_current ) ?>" />
					</label>
					<?php } ?>
				</span>
				<br class="cear" />
			</td>
		</tr>
	</tfoot>
	</table>
	<br class="clear" />
	<?php
	}
	
	static function admin_page() {
		global $revision_control;

		echo "<div class='wrap'>";
		echo '<h2>' . __('Revision Control Options', 'revision-control') . '</h2>';

		self::language_notice();

		echo '<h3>' . __('Default revision status for <em>Post Types</em>', 'revision-control') . '</h3>';
		
		if ( function_exists('post_type_supports') ) {
			$types = array();
			$_types = get_post_types();
			foreach ( $_types as $type ) {
				if ( post_type_supports($type, 'revisions') )
					$types[] = $type;
			}
		} else {
			$types = array('post', 'page');
		}

		echo '<form method="post" action="admin-post.php?action=revision-control-options">';
		wp_nonce_field('revision-control-options');

		echo '<table class="form-table">';
		echo '<tr valign="top">
				<th scope="row">' . __('Default Revision Status', 'revision-control') . '</th>
				<td><table>';
		foreach ( $types as $post_type ) {
			$post_type_name = $post_type;
			if ( !in_array($post_type, array('post', 'page')) && function_exists('get_post_type_object') ) {
				$pt = get_post_type_object($post_type);
				$post_type_name = $pt->label;
				unset($pt);
			} else {
				if ( 'post' == $post_type )
					$post_type_name = _n('Post', 'Posts', 5, 'revision-control');
				elseif ( 'page' == $post_type )
					$post_type_name = _n('Page', 'Pages', 5, 'revision-control');
					
			}

			echo '<tr><th style="width: auto;"><label for="options_per-type_' . $post_type . '"> <em>' . $post_type_name . '</em></label></th>';
			echo '<td align="left"><select name="options[per-type][' . $post_type . ']" id="options_per-type_' . $post_type . '">';
			$current = $revision_control->option($post_type, 'per-type');
			foreach ( $revision_control->get_revision_limit_select_items($current) as $option_val => $option_text ) {
				if ( 'defaults' == $option_val )
					continue;
				$selected = ($current == $option_val ? ' selected="selected"' : '');
				echo '<option value="' . esc_attr($option_val) . '"' . $selected . '>' . esc_html($option_text) . '</option>';
			}
			echo '</select></td></tr>';
		}
		echo '</table>';
		echo '
		</td>
		</tr>';
		echo '<tr>
		<th scope="row"><label for="options_revision-range">' . __('Revision Range', 'revision-control') . '</label></th>
				<td><textarea rows="2" cols="80" name="options[revision-range]" id="options_revision-range">' . esc_html($revision_control->option('revision-range')) . '</textarea><br />
				' . __('<em><strong>Note:</strong> This field is special. It controls what appears in the Revision Options <code>&lt;select&gt;</code> fields.<br />The basic syntax of this is simple, fields are seperated by comma\'s.<br /> A field may either be a number, OR a range.<br /> For example: <strong>1,5</strong> displays 1 Revision, and 5 Revisions. <strong>1..5</strong> on the other hand, will display 1.. 2.. 3.. 4.. 5.. Revisions.<br /> <strong>If in doubt, Leave this field alone.</strong></em>', 'revision-control') . '
				</td>
				</tr>';
		echo '</table>';
		submit_button( __('Save Changes', 'revision-control') );
		echo '
		</form>';
		echo '</div>';
	}

	static function language_notice( $force = false ) {
		$message_english = 'Hi there!
I notice you use WordPress in a Language other than English (US), Did you know you can translate WordPress Plugins into your native language as well?
If you\'d like to help out with translating this plugin into %1$s you can head over to <a href="%2$s">translate.WordPress.org</a> and suggest translations for any languages which you know.
Thanks! Dion.';
		/* translators: %1$s = The Locale (de_DE, en_US, fr_FR, he_IL, etc). %2$s = The translate.wordpress.org link to the plugin overview */
		$message = __( 'Hi there!
I notice you use WordPress in a Language other than English (US), Did you know you can translate WordPress Plugins into your native language as well?
If you\'d like to help out with translating this plugin into %1$s you can head over to <a href="%2$s">translate.WordPress.org</a> and suggest translations for any languages which you know.
Thanks! Dion.', 'revision-control' );

		// Don't display the message for English (US) or what we'll assume to be fully translated localised builds.
		if ( 'en_US' === get_locale() || ( $message == $message_english && ! $force  ) ) {
			return false;
		}

		$translate_url = 'https://translate.wordpress.org/projects/wp-plugins/revision-control/stable';

		echo '<div class="notice notice-info"><p>' . sprintf( nl2br( $message ), get_locale(), $translate_url ) . '</p></div>';
	}
}
