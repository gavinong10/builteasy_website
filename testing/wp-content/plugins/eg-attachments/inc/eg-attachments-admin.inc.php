<?php

if (! class_exists('EG_Attachments_Admin')) {

	/**
	 * Class EG_Attachments_Admin
	 *
	 * Implement a shortcode to display the list of attachments in a post.
	 *
	 * @package EG-Attachments
	 */
	Class EG_Attachments_Admin extends EG_Plugin_135 {

		var $edit_posts_pages = array('post.php', 'post-new.php', 'page.php', 'page-new.php');
		/* Q: what about other post type ? */

		var $post_id 	= FALSE;
		var $stats_hook = FALSE;

		/**
		 * init
		 *
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	none
		 * @return none
		 *
		 */
		function init() {

			parent::init();
		
			// Add a new post type
			register_post_type( EGA_TEMPLATE_POST_TYPE, array(
					'labels' => array(
						'name' 		    => __( 'EG-Attachment templates', $this->textdomain ),
						'singular_name' => __( 'EG-Attachment template', $this->textdomain )
					),
					'rewrite' 			  => false,
					'query_var' 		  => false,
					'exclude_from_search' => false,
					/* Added in EGA 2.0.3 */
					'public'			  => false,
					'publicly_queryable'  => false
				)
			);

			// If user requests button for TinyMCE, record it
			if ($this->options['tinymce_button']) {
				$this->add_tinymce_button( 'ega_shortcode', 'inc/tinymce/eg_attach_plugin.js');
			}

			// If user requests to be able to "tag" attachments
			if ($this->options['tags_assignment']) {
				register_taxonomy_for_object_type('post_tag', 'attachment');
			} // End of tags_assignment

		} // End of init

		/**
		 * admin_menu
		 *
		 *
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function admin_menu() {

			parent::admin_menu();

			if ($this->options['stats_enable']) {
				$this->stats_hook = add_submenu_page(
					'tools.php',
					esc_html__($this->name.' Stats', $this->textdomain),
					esc_html__('EGA Stats', $this->textdomain),
					EGA_VIEW_STATS,
					'ega_stats',
					array(&$this, 'display_stats')
				);
				add_action( 'load-' . $this->stats_hook, array(&$this, 'display_stats_load' ));
				add_action('admin_enqueue_scripts', array(&$this, 'display_stats_enqueue_scripts' ));
				add_action( 'admin_print_scripts-' . $this->stats_hook, array(&$this, 'display_stats_print_scripts' ));
			} // End of stats_enable

			// Add the page for the template edition
			$template_editor_page = add_submenu_page(
				'tools.php',
				esc_html__($this->name.' templates', $this->textdomain),
				esc_html__('EGA templates', $this->textdomain),
				EGA_READ_TEMPLATES,
				'ega_templates',
				array(&$this, 'template_editor')
			);

			// Add load and print_styles for this page
			add_action( 'load-' . $template_editor_page, array(&$this, 'template_editor_load' ));
			add_action( 'admin_print_styles-' . $template_editor_page, array(&$this, 'template_editor_styles' ));

			global $pagenow;
			if ($this->options['use_metabox'] && in_array($pagenow, $this->edit_posts_pages) ) {

				// Add metabox for posts
				add_meta_box( 'eg-attach-metabox', __( 'EG-Attachments', $this->textdomain ),
							array(&$this, 'display_metabox'), 'post', 'normal', 'high' );

				// Add metabox for pages
				add_meta_box( 'eg-attach-metabox', __( 'EG-Attachments', $this->textdomain ),
							array(&$this, 'display_metabox'), 'page', 'normal', 'high' );

			}
		} // End of admin_menu

		/**
		 * display_metabox
		 *
		 * Display attachments meta box
		 *
		 * @param	object	$post		post or page currently edited
		 * @param	array	$args		other arguments
		 * @return 	none
		 */
		function display_metabox($post, $args) {
			global $post;
?>
			<div id="egattach-stuff">
<?php
			$attachment_list  = get_posts( array(	'post_parent' 	=> $post->ID,
													'numberposts'	=> -1,
													'post_type'		=> 'attachment',
													'orderby'		=> 'excerpt',
													'order'			=> 'ASC'
												)
											);
			if ($attachment_list === FALSE && sizeof($attachment_list)==0) {
				esc_html_e('No document attached to this post/page', $this->textdomain);
			}
			else {
				$string = '<p>'.__('Attachments available for this post/page', $this->textdomain).'</p>'.
						'<table class="widefat fixed" >'.
						'<thead>'.
							'<tr>'.
								'<th>'.__('ID', $this->textdomain).'</th>'.
								'<th>'.__('File Name', $this->textdomain).'</th>'.
								'<th>'.__('Extension', $this->textdomain).'</th>'.
								'<th>'.__('Type', $this->textdomain).'</th>'.
								'<th>'.__('Size', $this->textdomain).'</th>'.
								'<th>'.__('Date', $this->textdomain).'</th>'.
							'</tr>'.
							'</thead>'.
							'<tbody>';
				foreach ($attachment_list as $attachment) {
					$file_path  = get_attached_file($attachment->ID);
					$file_type  = wp_check_filetype($file_path);
					$stat 		= stat($file_path);
					// $docsize = @filesize($file_path);
					$size_value = explode(' ',size_format($stat['size'], 0)); // WP function found in file wp-includes/functions.php
					$string .= '<tr class="alternate">'.
								'<td>'.$attachment->ID.'</td>'.
								'<td>'.wp_html_excerpt($attachment->post_title, 40).'</td>'.
								'<td>'.$file_type['ext'].'</td>'.
								'<td>'.wp_ext2type($file_type['ext']).'</td>'. // str_replace('vnd.','',str_replace('application/','',$file_type['type'])).'</td>'.
								'<td>'.( sizeof($size_value) < 2 ? '' : $size_value[0].' '.__($size_value[1], $this->textdomain) ).'</td>'.
								'<td>'.date(get_option('date_format'),$stat['mtime']).'</td>'.
							'</tr>';
				}
				$string .= '</tbody>'.
							'</table>';

				echo $string;
			}
?>
			</div>
<?php
		} // End of display_metabox

		/**
		 * template_editor_styles
		 *
		 * Load styles for the template editor
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function template_editor_styles() {
			wp_register_style( $this->name.'-admin', $this->url.'css/eg-attachments-admin.css');
			wp_enqueue_style( $this->name.'-admin' );
		} // End of template_editor_styles


		/**
		 * template_editor_load
		 *
		 * Get forms parameters, and execute requested actions
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function template_editor_load() {

			global $current_user;

			$cache_list_entry = strtolower($this->name).'-templates';
			$cache_shortcode_entry = strtolower($this->name).'-shortcode-tmpl';

			// Filetering the parameters
			extract(wp_parse_args($_REQUEST,
				array(
					'id' 			=> FALSE,
					'title'			=> '',
					'description'	=> '',
					'before'		=> '',
					'loop'			=> '',
					'after'			=> '',
					'action'		=> '',
					'action2'		=> '',
					'templates'		=> FALSE
				)
			));

			// Specifique cas for bulk delete
			if ( 'delete' == $action2 && -1 == $action && FALSE !== $templates) {
				$action = 'bulk-del';
			}

			switch (strtolower($action)) {
				case 'view':
				case 'edit':
					$this->post_id = $id;
				break;

				case 'save':
					if (check_admin_referer( 'ega_shortcodes_edit_nonce_field-'.$id )) {

						if (! current_user_can(EGA_DELETE_TEMPLATES)) {
							wp_die( __( 'You are not allowed to create/edit templates.', $this->textdomain ) );
						}

						$error = TRUE;
						$post = array(
						  'post_title'		=> trim($title),
						  'post_name'		=> '', /* Regenerate the slug */
						  'post_excerpt'	=> trim($description),
						  'post_content'	=> '[before]'.trim($before).'[/before]'.
											  '[loop]'.trim($loop).'[/loop]'.
											  '[after]'.trim($after).'[/after]',
						  'post_status'    	=> 'publish',
						  'post_type'      	=> EGA_TEMPLATE_POST_TYPE,
						  'comment_status' 	=> 'closed',
						  'ping_status'		=> 'closed'
						);

						if ('' == trim($title) )
							$query = array( 'action' => 'edit', 'id' => $id, 'msg' => 'errorsave', 'msg2' => 'titlenotdefined');
						elseif (__('New_template', $this->textdomain) == $title)
							$query = array( 'action' => 'edit', 'id' => $id, 'msg' => 'errorsave', 'msg2' => 'titlenotchanged');
						elseif ('' == trim($loop) )
							$query = array( 'action' => 'edit', 'id' => $id, 'msg' => 'errorsave', 'msg2' => 'loopnotdefined');
						else {
							if ('new' == $id)
								$result = wp_insert_post($post);
							else {
								$post['ID'] = (int)$id;
								$result = wp_update_post($post);
							}

							if (is_numeric($result) && $result > 0) {
								$query = array( 'action' => 'edit', 'msg' => ('new' == $id ? 'created' : 'saved'), 'id' => $result);
								$error = FALSE;
								delete_transient($cache_list_entry);
								delete_transient($cache_shortcode_entry);
							}
							else {
								$query = array( 'action' => 'edit', 'id' => $id, 'msg' => 'errorsave' );
							}
						} // End of if ok

						if (TRUE === $error) {
							$post = array_merge(
								array(
									'ID' 		=> $id,
									'before'	=> $before,
									'after'		=> $after,
									'loop'		=> $loop
								),
								$post);
							set_transient($this->name.'-'.absint($current_user->ID).'-tpl_edit', $post, 10);
						}
						wp_safe_redirect(
							add_query_arg(
								$query,
								menu_page_url( 'ega_templates', false )
							)
						);
					} // End of referrer ok
				break;

				case 'copy':
					if (check_admin_referer( 'ega_shortcodes_edit_nonce_field-'.$id )) {
						if (! current_user_can(EGA_CREATE_TEMPLATES)) {
							wp_die( __( 'You are not allowed to copy templates.', $this->textdomain ) );
						}

						$post = get_post(absint($id));
						if ($post) {
							$args = array(
								'post_title'	=> $post->post_title.'-copy',
								'post_name'		=> '',
								'post_excerpt'	=> __('Copy of ', $this->textdomain).$post->post_excerpt,
								'post_content'	=> $post->post_content,
								'post_author'	=> $current_user->ID,
								'post_status'	=> 'publish',
								'post_type'  	=> EGA_TEMPLATE_POST_TYPE,
								'comment_status' => 'closed',
								'ping_status'	=> 'closed'
							);
							$result = wp_insert_post($args);
							if (is_numeric($result) && $result > 0) {
								$query = array ('msg' => 'copied', 'id' => $result, 'action' => 'edit');
								delete_transient($cache_list_entry);
								delete_transient($cache_shortcode_entry);
							}
							else
								$query = array ('msg' => 'errorcopy', 'msg2' => 'errorcopy');
						} // End of post exist
						else {
							$query = array ('msg' => 'errorcopy', 'msg2' => 'unknownpost');
						}
						wp_safe_redirect(
							add_query_arg(
								$query,
								menu_page_url( 'ega_templates', false )
							)
						);
					} // End of check referrer
				break;

				case 'del':
					if (check_admin_referer( 'ega_shortcodes_edit_nonce_field-'.$id )) {
						if (! current_user_can(EGA_DELETE_TEMPLATES)) {
							wp_die( __( 'You are not allowed to delete templates.', $this->textdomain ) );
						}
						$result = wp_delete_post((int)$id);
						if (FALSE !== $result) {
							$query = array('msg' => 'deleted');
							delete_transient($cache_list_entry);
							delete_transient($cache_shortcode_entry);
						}
						else {
							$query = array('msg' => 'errordelete');
						}
						wp_safe_redirect(
							add_query_arg(
								$query,
								menu_page_url( 'ega_templates', false )
							)
						);
					} // End of check referrer
				break;

				case 'bulk-del':
					if (check_admin_referer( 'bulk-'.__('Templates', $this->textdomain))) {

						if (! current_user_can(EGA_DELETE_TEMPLATES)) {
							wp_die( __( 'You are not allowed to delete templates.', $this->textdomain ) );
						}

						$del_count = 0;
						foreach ($templates as $id_to_del) {
							$del_count += (FALSE === wp_delete_post((int)$id) ? 0 : 1);
						} // End of foreach
						delete_transient($cache_list_entry);
						delete_transient($cache_shortcode_entry);

						wp_safe_redirect(
							add_query_arg(
								array( 'msg' => 'bulkdeleted', 'itemdel' => $del_count, 'requested' => sizeof($templates)),
								menu_page_url( 'ega_templates', false )
							)
						);
					} // End of check referrer
				break;
			} // End of switch

			if (FALSE !== $id) {
				add_meta_box( 'template_form', __('Template', $this->textdomain), array(&$this, 'template_form_mt'), null, 'normal', 'core');
			}

		} // End of template_editor_load

		/**
		 * template_editor
		 *
		 * Display the template editor page
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function template_editor() {
?>
			<div class="wrap">
				<?php screen_icon(); ?>
				<h2>
					<?php esc_html_e($this->name.' templates', $this->textdomain); ?>
					<?php if ('new' != $this->post_id && current_user_can(EGA_CREATE_TEMPLATES)) { ?>
					<a href="<?php echo add_query_arg( array( 'id' => 'new', 'action' => 'edit' )); ?>" class="add-new-h2"><?php esc_html_e( 'Add New'); ?></a>
					<?php } ?>
				</h2>
				<?php $this->admin_notices(); ?>
				<br />
<?php
			// If ID is not defined, we go to the list of templates
			if (FALSE == $this->post_id) {
				$this->template_list();
			}
			else { // if ID is defined we go to the editor
				$this->template_edit();
			}
?>
			</div>
<?php
		} // End of template_editor

		/**
		 * template_list
		 *
		 * Display the template list
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function template_list() {
			// Get the class to manage the table
			if ( ! class_exists( 'EG_Attachments_Form_List_Table' ) )
				require_once ($this->path.'inc/eg-attachments-tools-form.inc.php');

			// Create the table
			$list_table = new EG_Attachments_Form_List_Table();
			$list_table->prepare_items();

			// Display it
?>
			<form method="get" action="">
				<input type="hidden" name="page" value="<?php esc_attr_e( $_REQUEST['page'] ); ?>" />
				<?php $list_table->display(); ?>
			</form>
<?php
		} // End of template_list

		/**
		 * template_edit
		 *
		 * Display the template editor
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function template_edit() {

			global $current_user;

			// An error occured. The fields edited in the previous form are stored in a transient
			$edited_fields = get_transient('eg-attachments-'.absint($current_user->ID).'-tpl_edit');
			if (FALSE !== $edited_fields) {
				$template = array(
					'id'			=> $edited_fields['ID'],
					'title'			=> $edited_fields['post_title'],
					'slug'			=> $edited_fields['post_name'],
					'description'	=> $edited_fields['post_excerpt'],
					'before'		=> $edited_fields['before'],
					'loop'			=> $edited_fields['loop'],
					'after'			=> $edited_fields['after']
				);
				delete_transient('eg-attachments-'.absint($current_user->ID).'-tpl_edit');
			} // End of cached fields
			else {
				// User is requesting a new template
				if ('new' == $this->post_id) {
					$template = array(
						'id'			=> 'new',
						'title'			=> __('New_template', $this->textdomain),
						'slug'			=> '',
						'description'	=> __('Description of the new template', $this->textdomain),
						'before'	    => '',
						'after'		    => '',
						'loop'			=> ''
					);
				} // End of new template
				else {
					// Request a template.
					$tmp = get_post($this->post_id);

					// Read post content, and extract before, loop, after
					$template = EG_Attachments_Common::parse_template($tmp->post_content);

					if (FALSE !== $template) {
						$template = array_merge($template, array(
							'id'			=> $tmp->ID,
							'title'			=> $tmp->post_title,
							'slug'			=> $tmp->post_name,
							'description'	=> $tmp->post_excerpt)
						);
					}
				} // End of edit template
			} // End of no cache
?>
			<div id="poststuff" class="metabox-holder">
				<?php do_meta_boxes( null, 'normal', $template); ?>
			</div>
<?php
		} // End of template_edit


		/**
		 * submit_buttons
		 *
		 * Display the submit buttons panel
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	string	disabled	disable the save button or not
		 * @return	none
		 *
		 */
		function submit_buttons($disabled) {
?>
			<p class="submit clear">
<?php		if ('' == $disabled) { ?>
				<input type="submit" value="<?php esc_attr_e('Save',   $this->textdomain); ?>" class="button button-primary button-large" id="ega_save" name="ega_save">
<?php		} ?>
				<a href="<?php echo menu_page_url( 'ega_templates', false ); ?>" title="<?php esc_html_e('Go back to the list', $this->textdomain); ?>" class="button button-large"><?php esc_html_e('Go back to the list', $this->textdomain); ?></a>
			</p>
<?php
		} // End of submit_buttons

		/**
		 * template_form_mt
		 *
		 * Display the template form itself
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	array	template	list of fields to edit
		 * @return	none
		 *
		 */
		function template_form_mt($template) {

			// Extract fields
			extract($template);

			// check the user right
			$disabled = ( current_user_can(EGA_EDIT_TEMPLATES, $id) ? '' : 'disabled');
?>
			<form method="post" action="<?php echo menu_page_url( 'ega_templates', false ); ?>">
			<input type="hidden" name="id" value="<?php echo $id; ?>" />
			<input type="hidden" name="action" value="save" />
			<?php wp_nonce_field( 'ega_shortcodes_edit_nonce_field-'.$id ); ?>
			<?php $this->submit_buttons($disabled); ?>
			<div class="ega-col-left">
			<p>
				<label for="title"><?php esc_html_e('Name of the template', $this->textdomain); ?></label><br />
				<input type="text" class="regular-text mandatory" name="title" value="<?php echo esc_attr($title); ?>" <?php echo $disabled; ?> />
				<?php if ($slug == '') { ?>
					<p class="description">
						<?php esc_html__('Choose a shortname, with standard characters (like a-z, 0-9, -_)', $this->textdomain); ?>
					</p>
				<?php } ?>
			</p>
			<p>
				<label for="description"><?php _e('Description', $this->textdomain); ?></label><br />
				<input type="text" class="large-text" name="description" value="<?php echo esc_attr(trim($description)); ?>" <?php echo $disabled; ?> />
			</p>
			<p>
				<label for="slug"><?php _e('Slug', $this->textdomain); ?></label><br />
				<input type="text" class="large-text" name="slug" value="<?php echo esc_attr(trim($slug)); ?>" disabled />
			</p>
			<p>
				<label for="before"><?php _e('Before', $this->textdomain); ?></label><br />
				<textarea cols="100" rows="5" class="large-text" name="before" <?php echo $disabled; ?>><?php echo esc_textarea( $before); ?></textarea>
			</p>
			<p>
				<label for="loop"><?php _e('Loop', $this->textdomain); ?></label><br />
				<textarea cols="100" rows="20" class="large-text mandatory" name="loop" <?php echo $disabled; ?>><?php echo esc_textarea( $loop); ?></textarea>
			</p>
			<p>
				<label for="after"><?php _e('After', $this->textdomain); ?></label><br />
				<textarea cols="100" rows="5" class="large-text" name="after" <?php echo $disabled; ?>><?php echo esc_textarea( $after); ?></textarea>
			</p>
			</div>
			<div class="ega-col-right" id="template-keywords">
				<?php require(dirname(__FILE__).'/eg-attachment-keywords-help.inc.php'); ?>
			</div>
			<?php $this->submit_buttons($disabled); ?>
			<br class="clear" />
			</form>
<?php
		} // End of template_form_mt

		/**
		 * admin_notices
		 *
		 * Display messages on top of the page (error or not)
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function admin_notices() {

			// Get messages ids
			$msg    	= isset( $_REQUEST['msg'] )			? $_REQUEST['msg'] 		: '';
			$msg2   	= isset( $_REQUEST['msg2'] ) 		? $_REQUEST['msg2']		: '';
			$itemdel 	= isset( $_REQUEST['itemdel'] )		? $_REQUEST['itemdel']	: 'unknown';
			$requested  = isset( $_REQUEST['requested'] ) 	? $_REQUEST['requested']: 'unknown';

			// If the id is containing error, then the message is an error
			$class = (FALSE !== strpos($msg, 'error') ? 'error' : 'updated');

			// List of messages
			$msg_list = array(
				'errorsave'			=> esc_html__('Error during template saving', $this->textdomain),
				'errorcopy'			=> esc_html__('Error during template copy', $this->textdomain),
				'saved'				=> esc_html__('Template successfully saved.', $this->textdomain),
				'created'			=> esc_html__('Template successfully created', $this->textdomain),
				'deleted'			=> esc_html__('Template sucessfully deleted', $this->textdomain),
				'errordelete'		=> esc_html__('Error during template deletion', $this->textdomain),
				'bulkdeleted'		=> sprintf(esc_html__('Bulk deletion done: %s on %s template(s) were deleted', $this->textdomain), $itemdel, $requested),
				'titlenotdefined'	=> esc_html__('Title is empty', $this->textdomain),
				'titlenotchanged'	=> sprintf(esc_html__('Title is still "%s"', $this->textdomain), __('New_template', $this->textdomain)),
				'loopnotdefined'	=> esc_html__('The field "loop" cannot be empty.', $this->textdomain),
				'errorcopy'			=> esc_html__( 'Error during the copy operation', $this->textdomain),
				'unknownpost'		=> esc_html__('The initial post is unknown or doesn\'t exist', $this->textdomain),
				'statdeleted'		=> sprintf(__('Purge of statistics: %d lines deleted (Retention: %d months).', $this->textdomain),$itemdel, $this->options['purge_stats'])

			);

			// Display message
			if (isset($msg_list[$msg])) {
			?>
				<div id="message" class="<?php echo $class; ?>">
					<p><?php echo $msg_list[$msg]; ?></p>
					<?php if (isset($msg_list[$msg2])) { ?>
					<p><?php echo $msg_list[$msg2]; ?></p>
					<?php } ?>
				</div>
			<?php
			}
		} // End of admin_notices


		/**
		 * install_upgrade
		 *
		 * Create or update options, DB table, ...
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function install_upgrade() {
			global $wpdb;

			$previous_options = parent::install_upgrade();
			$previous_version = ($previous_options === FALSE ? '0.0.0' : $previous_options['version']);
			if (version_compare($this->version, $previous_version)>0) {

				/**
				 * From version 1.4.3
				 */
				if (isset($this->options['uninstall_del_option'])) {
					$this->options['uninstall_del_options'] = $previous_options['uninstall_del_option'];
					unset($this->options['uninstall_del_option']);
					update_option($this->options_entry, $this->options);
				} // End of version older than 1.4.3

				/**
				 * From version 1.7.3 to 1.9.x
				 */
				if ( isset($previous_options['shortcode_auto_format_pre'])) {

					$changed_options = array(
									'shortcode_auto_format_pre'  => 'custom_format_pre',
									'shortcode_auto_format'      => 'custom_format',
									'shortcode_auto_format_post' => 'custom_format_post');

					foreach ($changed_options as $old_option => $new_option) {
						if (isset($previous_options[$old_option])) {
							$previous_options[$new_option] = $previous_options[$old_option];
							unset($previous_options[$old_option]);
						}
					} // End of foreach
				} // End of version older than 1.7.3

				/**
				 * From version 1.x.x to 1.9.2
				 */
				if (! is_array($this->options['shortcode_auto_where'])) {
					if ($this->options['shortcode_auto_where'] == 'post')
						$this->options['shortcode_auto_where'] = array( 'post', 'page');
					else
						$this->options['shortcode_auto_where'] = array( 'home', 'post', 'page', 'index');

					update_option($this->options_entry, $this->options);
				} // End of modify shortcode_auto_where

				/**
				 * From 1.9.x to 2.x
				 */

				/* ---- Create default templates --- */
				// Get the number of templates installed
				//$templates_count = (array)wp_count_posts( EGA_TEMPLATE_POST_TYPE );
				$templates = get_posts(array(
							'post_status' 	=> 'publish',
							'post_type'		=> EGA_TEMPLATE_POST_TYPE,
							'numberposts' 	=> -1
						)
					);
				if (FALSE === $templates || 0 == sizeof($templates)) {

					// No templates installed. Creating standard templates.
					$path = $this->path.'inc/templates';
					if ($handle = opendir($path)) {

						while (($file = readdir($handle)) !== false) {
							if ($file != '..' && $file != '.') {

// eg_plugin_error_log('EGA', 'File', $file);
							
								$string = file_get_contents($path.'/'.$file);
								
								preg_match_all('/\[title\](.*)\[\/title\](.*)\[description\](.*)\[\/description\](.*)/is', $string, $matches);
								
								if ( 4 < sizeof($matches) && 0 < sizeof($matches[0]) ) {
									
									$template = array(
										'post_status' 	=> 'publish', 
										'post_type' 	=> EGA_TEMPLATE_POST_TYPE,
										'ping_status' 	=> 'closed', 
										'post_excerpt'	=> trim($matches[3][0]),
										'post_content'	=> trim($matches[4][0]),
										'post_title'	=> trim($matches[1][0]),
										'comment_status'=> 'closed'
									);
									
// eg_plugin_error_log('EGA', 'New template', $template);
									
									$new_id = wp_insert_post($template);
									if (is_numeric($new_id) && $new_id > 0) {
										$this->options['standard_templates'] .= ('' == $this->options['standard_templates'] ? '' : ',').$new_id;
										update_option($this->options_entry, $this->options);
									} // End of insert post succeed
								} // End of preg_match_all matching
							} // End file != .. and .
						} // End of while
						closedir($handle);
					} // End of if opendir
				} // End of no template defined

				/* --- Convert custom format --- */
				if ( (isset($previous_options['custom_format_pre'])  && $previous_options['custom_format_pre']  != '') ||
					 (isset($previous_options['custom_format']) 	 && $previous_options['custom_format']      != '') ||
					 (isset($previous_options['custom_format_post']) && $previous_options['custom_format_post'] != '')) {

					$args = array(
						'post_title'     => esc_html__('Custom', $this->textdomain),
						'post_excerpt'   => sprintf(__('Custom format from previous version %s', $this->textdomain), $previous_version),
						'post_content'   => '[before]'.trim(isset($previous_options['custom_format_pre']) ? $previous_options['custom_format_pre'] : '').'[/before]'.
								  '[loop]'.trim(isset($previous_options['custom_format']) ? $previous_options['custom_format'] : '').'[/loop]'.
								  '[after]'.trim(isset($previous_options['custom_format_post']) ? $previous_options['custom_format_post'] : '').'[/after]',
						'post_status'    => 'publish',
						'post_type'      => EGA_TEMPLATE_POST_TYPE,
						'comment_status' => 'closed',
						'ping_status'	 => 'closed'
					);
					$new_id = wp_insert_post($args);
					if (is_numeric($new_id) && $new_id > 0) {
						$new_post = get_post($new_id);
						if ('custom' == $previous_options['shortcode_auto_size']) {
							$this->options['shortcode_auto_template'] = $new_post->post_name;
						}
						$this->options['legacy_custom_format'] = $new_post->post_name;
						update_option($this->options_entry, $this->options);
					} // End of post created succeeded
				} // End of custom_format

				if (isset($previous_options['shortcode_auto_size']) && '' != $previous_options['shortcode_auto_size']) {
					$this->options['shortcode_auto_template'] = $previous_options['shortcode_auto_size'];
					if ('custom' != $previous_options['shortcode_auto_size'] &&
						(! isset($previous_options['shortcode_auto_icon']) || 0 == $previous_options['shortcode_auto_icon'])) {
						// Size is becoming depredicated.
						// $this->options['shortcode_auto_size'] = $previous_options['shortcode_auto_size'].'-list';
						$this->options['shortcode_auto_template'] .= '-list';
					}
					update_option($this->options_entry, $this->options);
				} // End of shortcode_auto_size

				/* --- Version upper than 2.0.0 --- */
				if ( TRUE == version_compare($this->version, '2.0.0', ">" ) &&
					 TRUE == version_compare($previous_version, '2.0.0', ">=" ) ) {
						$templates = get_posts(array( 'post_type' => EGA_TEMPLATE_POST_TYPE ) );

						foreach ($templates as $template) {
							$updated_post = array(
								'ID'           => (int) $template->ID,
								'post_type'	   => EGA_TEMPLATE_POST_TYPE,
								'post_content' => str_replace('%LINK%', '%URL%', $template->post_content)
							);

							wp_update_post($updated_post);
						} // End of foreach $template

				} // End of %LINK% replaced by %URL%


			} // End of update


			$table_name = $wpdb->prefix . 'eg_attachments_clicks';
			if ( 1 > floatval($this->options['clicks_table']) ) {

				$sql = "CREATE TABLE " . $table_name . " (
						click_id bigint(20) NOT NULL auto_increment,
						click_date datetime NOT NULL default '0000-00-00 00:00:00',
						attach_id bigint(20) unsigned,
						attach_title text NOT NULL,
						post_id bigint(20) unsigned,
						post_title text NOT NULL,
						clicks_number int(10) NOT NULL,
						PRIMARY KEY  (click_date,post_id,attach_id),
						KEY  click_date (click_date),
						KEY  click_id (click_id)
					);";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);

				$this->options['clicks_table'] = '1.1';
				update_option($this->options_entry, $this->options);
			} // End of first install
			elseif ( 1.1 > floatval($this->options['clicks_table']) ) {

				$sql = 'ALTER TABLE '.$table_name."\n".
						' DROP KEY attach_date, '."\n".
						' DROP KEY date_attach_post, '."\n".
						' ADD PRIMARY KEY (click_date,post_id,attach_id);';

				$results = $wpdb->query( $sql );
				if ( ! $results ) {
					$this->options['clicks_table'] = '1.1';
					update_option($this->options_entry, $this->options);
				}
			} // End of update to 1.1

			if ( ! $this->options['on_duplicate'] &&
				version_compare($this->version, $previous_version) > 0 &&
				version_compare($this->version, '2.0.2') > 0 ) {

				$tableindices = $wpdb->get_results("SHOW INDEX FROM {$table_name};");

				if ( $tableindices ) {
// eg_plugin_error_log('EGA', 'Test indexes');
					// For every index in the table
					foreach ($tableindices as $tableindex) {
						// Add the index to the index data array
						$keyname = $tableindex->Key_name;
						$index_list[$keyname]['columns'][] = $tableindex->Column_name;
						$index_list[$keyname]['unique']    = ($tableindex->Non_unique == 0 ? true : false);
					} // End of foreach

					if ( isset($index_list['PRIMARY']) && sizeof($index_list['PRIMARY']['columns']) > 2 ) {
						sort($index_list['PRIMARY']['columns'], SORT_STRING);
						if ( strtolower( implode(',', $index_list['PRIMARY']['columns'])) == 'attach_id,click_date,post_id' ) {
// eg_plugin_error_log('EGA', 'Primary key found with column'.strtolower(implode(',', $index_list['PRIMARY']['columns'])));
							$this->options['on_duplicate'] = 1;
						}
					}
					elseif ( isset($index_list['date_attach_post']) ) {
						if ( $index_list['date_attach_post']['unique'] ) {
// eg_plugin_error_log('EGA', 'date_attach_post key found and it is unique');
							$this->options['on_duplicate'] = 1;
						}
					}
					update_option($this->options_entry, $this->options);
				} // End of get index / keys

			} // End of (New version && version > 2.0.2)

		} // End of install_upgrade

		/**
		 * display_stats_enqueue_scripts
		 *
		 * Load scripts and styles for date picker
		 *
		 * @package EG-Attachments
		 * @since 	2.0
		 *
		 * @param 	none
		 * @return none
		 *
		 */
		function display_stats_enqueue_scripts($hook) {

			if ($hook == $this->stats_hook) {
				wp_enqueue_script('jquery-ui-datepicker');

				wp_register_style( $this->name.'-datepicker', $this->url.'css/eg-attachments-datepicker.css' );
				wp_enqueue_style($this->name.'-datepicker');

				add_action('admin_footer', array(&$this, 'display_stats_footer') );
			} // End if stat page
		} // End of displat_stats_enqueue_scripts

		/**
		 * display_stats_print_scripts
		 *
		 * Load google library
		 *
		 * @package EG-Attachments
		 * @since 	2.0
		 *
		 * @param 	none
		 * @return none
		 *
		 */
		function display_stats_print_scripts() {
	?>
			<script type="text/javascript" src="https://www.google.com/jsapi" ></script>
	<?php
		} // End of display_stats_scripts

		/**
		 * display_stats_load
		 *
		 * Load function for the statistic page.
		 * Purge statistics table
		 *
		 * @package EG-Attachments
		 * @since 	2.0
		 *
		 * @param 	none
		 * @return none
		 *
		 */
		function display_stats_load() {
			global $wpdb;

			if (absint($this->options['purge_stats']) != 0) {
				// Purge date: number of months * 30 (average number of days per month) * 24 * 60 * 60
				$purge_date = time() - $this->options['purge_stats'] * 30 * 24 * 60 * 60;

				// Purge statistics table
				$sql = $wpdb->prepare('DELETE FROM '.$wpdb->prefix.'eg_attachments_clicks '.
						'WHERE click_date<%s', date('Y-m-d H:i:s', $purge_date));
				$status = $wpdb->query($sql);
				if ($status > 0) {
					wp_safe_redirect(
						add_query_arg(
							array( 'msg' => 'statdeleted', 'itemdel' => $status),
							menu_page_url( 'ega_stats', false )
						)
					);
				} // End of line deleted
			} // End of purge_stats activated
		} // End of display_stats_load

		/**
		 * display_stats
		 *
		 * Display statistics page
		 *
		 * @package EG-Attachments
		 * @since 	1.7.0
		 *
		 * @param 	none
		 * @return none
		 *
		 */
		function display_stats() {
?>
			<div class="wrap">
				<?php screen_icon(); ?>
				<h2><?php esc_html_e($this->name.' Stats', $this->textdomain); ?></h2>
				<?php $this->admin_notices(); ?>
				<br />
<?php
			if ( isset($_REQUEST['aid']) && is_numeric($_REQUEST['aid']) )
				$this->display_stats_details(absint($_REQUEST['aid']));
			else
				$this->display_stats_global();
?>
			</div>
<?php
		} // End of display_stats

		/**
		 * display_stat_groupby_where
		 *
		 * Define the where sentence, and the groupby sentence for the statistics query
		 *
		 * @package EG-Attachments
		 * @since 	2.0.0
		 *
		 * @param 	none
		 * @return none
		 *
		 */
		function display_stat_groupby_where($begin_date, $end_date, $step_date, $attach_id=FALSE) {

			switch ($step_date) {
				case 'month':
					$group_by 			= 'YEAR(click_date),MONTH(click_date)';
					$mysql2date_format	= 'M Y';
					$axis_title		  	= __('Month', $this->textdomain);
				break;

				case 'week' :
					$group_by			= 'YEAR(click_date),WEEKOFYEAR(click_date)';
					$mysql2date_format	= __('\W', $this->textdomain).'W Y';
					$axis_title		  	= __('Week', $this->textdomain);
				break;

				default:
					$group_by			= 'click_date';
					$mysql2date_format	= 'd-M-Y';
					$axis_title		  	= __('Day', $this->textdomain);
				break;
			} // End of switch

			return array(
				'where' 			=> ($attach_id ? 'attach_id = '.$attach_id. ' AND ' : '').
										'click_date>=FROM_UNIXTIME('.strtotime($begin_date).') '.
										'AND click_date<=FROM_UNIXTIME('.strtotime($end_date).')',
				'group_by' 			=> $group_by,
				'mysql2date_format'	=> $mysql2date_format,
				'axis'				=> $axis_title
			);
		} // End of display_stat_groupby_where

		/**
		 * display_stats_history_graph
		 *
		 * Display the date filter, and the graph of the statistics page
		 *
		 * @package EG-Attachments
		 * @since 	2.0.0
		 *
		 * @param 	none
		 * @return none
		 *
		 */
		function display_stats_history_graph($begin_date, $display_begin, $end_date, $display_end, $step_date, $sql_params, $attach_id=FALSE) {
			global $wpdb;
			$total_download = 0;

			if ($attach_id)
				$link = add_query_arg(array('aid' => $attach_id), menu_page_url( 'ega_stats', false ));
			else
				$link = menu_page_url( 'ega_stats', false );
?>
			<form method="post" action="<?php echo $link; ?>">
				<p class="search-box">
				<label for="begin_date"><?php esc_html_e('Start date: ', $this->textdomain); ?></label>
				<input type="text" id="display_begin" name="display_begin" value="<?php echo $display_begin; ?>" class="begin-end-datepicker small" />
				<input type="hidden" id="begin_date" name="begin_date" value="<?php echo $begin_date; ?>" class="begin-end-datepicker small" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<label for="end_date"><?php esc_html_e('End date: ', $this->textdomain); ?></label>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" id="display_end" name="display_end" value="<?php echo $display_end; ?>" class="begin-end-datepicker small" />
				<input type="hidden" id="end_date" name="end_date" value="<?php echo $end_date; ?>" class="begin-end-datepicker small" />
				<label for="step_date"><?php esc_html_e('by ', $this->textdomain); ?></label>
				<select id="step_date" name="step_date">
					<option value="day" <?php echo ('day' == $step_date ? 'selected' : ''); ?> ><?php _e('Day', $this->textdomain); ?></option>
					<option value="week" <?php echo ('week' == $step_date ? 'selected' : ''); ?> ><?php _e('Week', $this->textdomain); ?></option>
					<option value="month" <?php echo ('month' == $step_date ? 'selected' : ''); ?> ><?php _e('Month', $this->textdomain); ?></option>
				</select>
				<input type="submit" id="submit_date" name="submit_date" value="<?php esc_html_e('Display chart', $this->textdomain); ?>" class="button" />
				</p>
			</form>
			<br style="clear: both;" />
<?php

			$sql = 'SELECT click_date, SUM(clicks_number) as total'.
				' FROM '.$wpdb->prefix.'eg_attachments_clicks '.
				' WHERE '.$sql_params['where'].
				' GROUP BY '.$sql_params['group_by'].
				' ORDER BY click_date ASC';

			$results = $wpdb->get_results($sql);
			$data = array();
			if (! $results) {
				echo '<p>'.__('No data available', $this->textdomain ).'</p>';
			}
			else {
				$data[] = array($sql_params['axis'], ucfirst(esc_html__('clicks', $this->textdomain)) );

				foreach ($results as $result) {
					$data[] = array(mysql2date($sql_params['mysql2date_format'], $result->click_date, TRUE),absint($result->total));
					$total_download += absint($result->total);
				}


				$lang = explode('-', get_bloginfo('language'));
				if (is_array($lang))
					$lang = $lang[0];
	?>
				<script type="text/javascript">
					google.load("visualization", "1", {packages:["corechart"], 'language': '<?php echo $lang; ?>'});
					google.setOnLoadCallback(drawChart);

					function drawChart() {
						var data = google.visualization.arrayToDataTable(
							<?php echo json_encode($data); ?>
						);

						var options = {
							title: "<?php esc_html_e('Number of downloads', $this->textdomain); ?>"
						};

						var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
						chart.draw(data, options);
					}
				</script>
				<br />
				<div id="chart_div" style="margin: 0 auto; width: 98%; height: 350px; background-color: #efefef; border: 1px solid #eee">
				</div>
<?php
			} // End of if $results
?>
			<br />
<?php
			return ($total_download);
		} // End of display_stats_history_graph

		/**
		 * display_stats_sheet
		 *
		 * Display the detailed statistics, below the graph
		 *
		 * @package EG-Attachments
		 * @since 	2.0.0
		 *
		 * @param 	array	$results			list of attachments / posts
		 * @param	integer	$total_download		total number of download
		 * @param	string	$type				attachment or post
		 * @return none
		 *
		 */
		function display_stats_sheet($results, $total_download, $type='attachment') {
?>
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<thead>
					<tr>
						<th scope="col" class="column-cb check-column">&nbsp;</th>
						<th scope="col"><?php esc_html_e('Title'); ?></th>
						<th scope="col"><?php esc_html_e('Click(s)', $this->textdomain); ?></th>
						<th scope="col"><?php esc_html_e('% Clicks', $this->textdomain); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col" class="column-cb check-column">&nbsp;</th>
						<th scope="col"><?php esc_html_e('Title'); ?></th>
						<th scope="col"><?php esc_html_e('Click(s)', $this->textdomain); ?></th>
						<th scope="col"><?php esc_html_e('% Clicks', $this->textdomain); ?></th>
					</tr>
				</tfoot>
				<tbody>
<?php
			$i = 1;
			$alternate = 'alternate';
			reset($results);
			foreach ($results as $result) {
				$percent = round(($result->total / $total_download) * 100, 2);
				if ('attachment' == $type)
					$link = add_query_arg(array('aid' => $result->id), menu_page_url( 'ega_stats', false ));
				else
					$link = get_permalink($result->id);

				echo '<tr '.('' == $alternate ? $alternate = 'class="alternate"' : $alternate = '').'">'."\n".
					'<th class="check-column" scope="row">'.$i++.'</th>'."\n".
					'<td><a href="'.$link.'">'.esc_html($result->title).'</a></td>'."\n".
					'<td>'.$result->total.'</td>'."\n".
					'<td class="percent"><div style="width: '.($percent * 0.8).'%;"></div>'.$percent.'%</td>'."\n".
					'</tr>'."\n";
			} // End of foreach
?>
				</tbody>
			</table>
<?php
		} // End of display_stats_sheet

		/**
		 * display_stats_global
		 *
		 * Display statistics page
		 *
		 * @package EG-Attachments
		 * @since 	1.5.0
		 *
		 * @param 	none
		 * @return none
		 *
		 */
		function display_stats_global() {
			global $wpdb;

			$end_date		= (isset($_POST['end_date']) ? $_POST['end_date'] : date_i18n('Y-m-d'));
			$display_end	= (isset($_POST['display_end']) ? $_POST['display_end'] : date_i18n('d-M-Y'));

			$begin_date 	= (isset($_POST['begin_date']) ? $_POST['begin_date'] : date_i18n('Y-m-d', strtotime('-1 year') ) );
			$display_begin 	= (isset($_POST['display_begin']) ? $_POST['display_begin'] : date_i18n('d-M-Y', strtotime('-1 year') ) );

			$step_date		= (isset($_POST['step_date']) ? $_POST['step_date'] : 'month' );
			$sql_params 	= $this->display_stat_groupby_where($begin_date, $end_date, $step_date);

			$total_download = $this->display_stats_history_graph($begin_date, $display_begin, $end_date, $display_end, $step_date, $sql_params);

			$per_page = 25;
			$paged = (isset($_GET['paged']) ? $_GET['paged'] : 1);

			$sql = 'SELECT COUNT(DISTINCT(attach_id))'.
					' FROM '.$wpdb->prefix.'eg_attachments_clicks ';
			$attachments_number = $wpdb->get_var($sql);
			$page_number = ceil($attachments_number / $per_page);

			$sql = 'SELECT attach_id as id, attach_title as title, SUM(clicks_number) as total'.
				' FROM '.$wpdb->prefix.'eg_attachments_clicks '.
				' WHERE '.$sql_params['where'].
				' GROUP BY attach_id'.
				' ORDER BY total DESC'.

				' LIMIT '.($paged-1)*$per_page.','.$per_page;
			$results = $wpdb->get_results($sql);

			$first_link     = menu_page_url( 'ega_stats', false );
			$last_link		= add_query_arg(array('paged' => $page_number), menu_page_url( 'ega_stats', false ));
			$previous_link 	= add_query_arg(array('paged' => max(1, $paged-1)), menu_page_url( 'ega_stats', false ) );
			$next_link		= add_query_arg(array('paged' => min($page_number, $paged+1)), menu_page_url( 'ega_stats', false ) );
?>
			<div class="tablenav top">
				<div class="tablenav-pages">
					<span class="displaying-num <?php echo (1==$paged?'disabled':''); ?>">
						<?php sprintf( _n( '1 item', '%s items', $attachments_number ), number_format_i18n( $attachments_number ) ); ?>
					</span>
					<span class="pagination-links">
						<a href="<?php echo $first_link; ?>" title="<?php esc_attr_e( 'Go to the first page' ); ?>" class="first-page <?php echo ($page_number==$paged?'disabled':''); ?>">&laquo;</a>
						<a href="<?php echo $previous_link; ?>" title="<?php esc_attr_e( 'Go to the previous page' ); ?>" class="prev-page <?php echo ($page_number==$paged?'disabled':''); ?>">&lsaquo;</a>
						<span class="paging-input">
						<?php echo $paged; ?> <?php esc_html_e('of'); ?> <span class="total-pages"><?php echo $page_number; ?></span>
						</span>
						<a href="<?php echo $next_link; ?>" title="<?php esc_attr_e( 'Go to the next page' ); ?>" class="next-page">&rsaquo;</a>
						<a href="<?php echo $last_link; ?>" title="<?php esc_attr_e( 'Go to the last page' ); ?>" class="last-page">&raquo;</a>
					</span>
				</div>
			</div>
<?php
			$this->display_stats_sheet($results, $total_download);
		} // End of stats_display_global

		/**
		 * display_stats_details
		 *
		 * Display statistics page for a specific attachment
		 *
		 * @package EG-Attachments
		 * @since 	1.5.0
		 *
		 * @param 	integer		$attach_id		id of the attachment
		 * @return none
		 *
		 * TODO: include an alert about the table size?
		 */
		function display_stats_details($attach_id) {
			global $wpdb;

			$attach_title = $wpdb->get_var('SELECT attach_title FROM '.$wpdb->prefix.'eg_attachments_clicks WHERE attach_id='.$attach_id);
?>
			<ul class="subsubsub">
				<li><a href="<?php echo menu_page_url( 'ega_stats', false ); ?>"><?php esc_html_e('Global'); ?></a> &gt;</li>
				<li><?php echo esc_html($attach_title); ?></li>
			</ul>
<?php
			$end_date		= (isset($_POST['end_date']) ? $_POST['end_date'] : date_i18n('Y-m-d'));
			$display_end	= (isset($_POST['display_end']) ? $_POST['display_end'] : date_i18n('d-M-Y'));

			$begin_date 	= (isset($_POST['begin_date']) ? $_POST['begin_date'] : date_i18n('Y-m-d', strtotime('-1 year') ) );
			$display_begin 	= (isset($_POST['display_begin']) ? $_POST['display_begin'] : date_i18n('d-M-Y', strtotime('-1 year') ) );

			$step_date	= (isset($_POST['step_date']) ? $_POST['step_date'] : 'month' );

			$sql_params = $this->display_stat_groupby_where($begin_date, $end_date, $step_date, $attach_id );

			$total_download = $this->display_stats_history_graph($begin_date, $display_begin, $end_date, $display_end, $step_date, $sql_params, $attach_id);

			$sql = 'SELECT post_id as id, post_title as title, SUM(clicks_number) as total'.
				' FROM '.$wpdb->prefix.'eg_attachments_clicks '.
				' WHERE '.$sql_params['where'].
			/*	' GROUP BY attach_title'. */
				' GROUP BY attach_id'.
				' ORDER BY total DESC';
			$results = $wpdb->get_results($sql);
			$this->display_stats_sheet($results, $total_download, 'post');

		} // End of stats_display_details

		/**
		 * display_stats_footer
		 *
		 * Include the datepicker
		 *
		 * @package EG-Attachments
		 * @since 	2.0.0
		 *
		 * @param 	none
		 * @return none
		 */
		function display_stats_footer() {
?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery('#display_begin').datepicker( { dateFormat: 'dd-M-yy', altField: "#begin_date", altFormat: "yy-mm-dd" } );
					jQuery('#display_end').datepicker( { dateFormat: 'dd-M-yy', altField: "#end_date", altFormat: "yy-mm-dd" } );
				});
			</script>
<?php
		} // End of admin_footer


		/**
		 * add_menu_to_admin_bar
		 *
		 * Add a menu for EG-Attachments in the admin bar
		 *
		 * @package EG-Attachments
		 * @since 	2.0.0
		 *
		 * @param 	object	$wp_admin_bar		the admin bar
		 * @return none
		 *
		 */
		function add_menu_to_admin_bar($wp_admin_bar) {

			$this->adminbar_menu[] = array(
				'menu' => array(
						'id' 	 => sanitize_title($this->name).'-templates',
						'title'  => __($this->name.' Templates', $this->textdomain),
						'href' 	 => admin_url('tools.php?page=ega_templates')),
				'cap'  => EGA_READ_TEMPLATES
			);

			$this->adminbar_menu[] = array(
				'menu' => array(
						'id' 	 => sanitize_title($this->name).'-stats',
						'title'  => __($this->name.' Statistics', $this->textdomain),
						'href' 	 => admin_url('tools.php?page=ega_stats')),
				'cap'  => EGA_VIEW_STATS
			);
			parent::add_menu_to_admin_bar($wp_admin_bar);
		} // End of add_menu_to_admin_bar

		/**
		 * pointer_ega_templates
		 *
		 * Add a pointer to show the new menu EGA-Templates
		 *
		 * @package EG-Attachments
		 * @since 	2.0.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function pointer_ega_templates() {
			$content  = '<h3>' . $this->name.'<br />'. __( 'Multiple custom formats', $this->textdomain ) . '</h3>';
			$content .= '<p>' .  __( 'EG-Attachments can now manage multiple custom formats. A new menu allows you to edit &laquo;templates&raquo;', $this->textdomain ) . '</p>';

			$this->footer_pointers_scripts( 'ega_templates', '#menu-tools', array(
				'content'  => $content,
				'position' => array( 'edge' => 'left', 'align' => 'middle' )
			) );
		} // End of pointer_ega_templates

		function pointer_ega_stats() {
			$content  = '<h3>' . $this->name.'<br />'.__( 'New statistics', $this->textdomain ) . '</h3>';
			$content .= '<p>' .  __( 'The statistics module is using <strong>Google Chart Tools</strong> for better graphes.', $this->textdomain ) . '</p>';

			$this->footer_pointers_scripts( 'ega_stats', '#menu-tools', array(
				'content'  => $content,
				'position' => array( 'edge' => 'left', 'align' => 'middle' )
			) );
		} // End of pointer_ega_stats

		/**
		 * pointer_ega_tinymce_button
		 *
		 * Add a pointer for the new option (show/hide TinyMCE button)
		 *
		 * @package EG-Attachments
		 * @since 	2.0.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function pointer_ega_tinymce_button() {
			$content  = '<h3>' . $this->name.'<br />'.__( 'Show or hide TinyMCE button', $this->textdomain ) . '</h3>';
			$content .= '<p>' .  __( 'You can now show or hide TinyMCE button in the new/edit post/page ', $this->textdomain ) . '</p>';

			$this->footer_pointers_scripts( 'ega_tinymce_button', '#tinymce_button', array(
				'content'  => $content,
				'position' => array( 'edge' => 'top', 'align' => 'center' ),
			) );
		} // End of pointer_ega_tinymce_button

		/**
		 * pointer_ega_exclude_featured
		 *
		 * Add a pointer for the new option (exclude thumbnail)
		 *
		 * @package EG-Attachments
		 * @since 	2.0.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function pointer_ega_exclude_featured() {
			$content  = '<h3>' . $this->name.'<br />'.__( 'Exclude thumbnail', $this->textdomain ) . '</h3>';
			$content .= '<p>' .  __( 'You can now, automatically exclude the featured image from the list of attachments to be displayed.', $this->textdomain ) . '</p>';

			$this->footer_pointers_scripts( 'ega_exclude_featured', '#exclude_featured', array(
				'content'  => $content,
				'position' => array( 'edge' => 'top', 'align' => 'center' ),
			) );
		} // End of pointer_ega_exclude_featured

		/**
		 * pointer_ega_template_keywords
		 *
		 * Add a pointer to highlight the keywords to be used for templates
		 *
		 * @package EG-Attachments
		 * @since 	2.0.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function pointer_ega_template_keywords() {
			$content  = '<h3>' . $this->name.'<br />'.__( 'Keywords', $this->textdomain ) . '</h3>';
			$content .= '<p>' .  __( 'Use these keywords to build you own template.', $this->textdomain ) . '</p>';

			$this->footer_pointers_scripts( 'ega_template_keywords', '#template-keywords', array(
				'content'  => $content,
				'position' => array( 'edge' => 'right', 'align' => 'top' ),
			) );
		} // End of pointer_ega_template_keywords

		/**
		 * options_validation
		 *
		 * Validate outputs
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 * @param	array	input	list of fields of the option form
		 * @return	string			all updated options
		 *
		 */
		function options_validation($inputs) {
			$all_options = parent::options_validation($inputs);

			if ( FALSE !== $this->changed_options ) {
			
				// Check request for cache clearance
				if ( isset($this->changed_options['clear_cache']) &&
					FALSE !== $all_options['clear_cache'] ) {

					foreach ($this->changed_options['clear_cache'] as $key) {
						if ( '' != $key ) {
							$this->clear_cache($key);
						}
					} // End of foreach list of cache
				} // End of clear cache
				
				// Check icon path
				if ( isset( $this->changed_options['icon_path'] ) ) {
				
					$icon_path = trim($all_options['icon_path']);
					if ('' != $icon_path ) {
					
						if ( ! file_exists( path_join( ABSPATH, $icon_path ) ) ) {
							add_settings_error(
									'ega_options', 
									'path-not-exist', 
									sprintf(__('The path %1s specified in the section <a href="%2s">Icons set</a>, doesn\'t exist', $this->textdomain), $icon_path, '#icon_path'),
									'error'
								);
						} // End of icon_path doesn't exist
						elseif ( ! is_dir( path_join( ABSPATH, $icon_path ) ) ) {
							add_settings_error(
									'ega_options', 
									'path-not-dir',
									sprintf(__('The path %1s specified in the section <a href="%2s">Icons set</a>, is not a directory.', $this->textdomain), $icon_path, '#icon_path'),
									'error'
								);
						}
					} // End of icon_path not empty
					else {
						// if icon_path='', ensure that icon_url is also empty
						$all_options['icon_url'] = '';
					}
				} // End of icon_path changed
				
			} // End of check changed options

			return ($all_options);
		} // End of function options_validation

		/**
		 * update_attachement
		 *
		 * Clear cache according a specific attachment
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 * @param	int		$id		id the attachment
		 * @return	none
		 *
		 */
		function update_attachement($id) {

			// Added in 2.0.3 - Clear cache containing all attachments ( id=-1 )
			$this->clear_cache('all');

			if ( is_numeric($id) && 0 < $id ) {
				$post_id = wp_get_post_parent_id( $id );
				if ( $post_id )
					$this->clear_cache($post_id);
			}
		} // End of delete_attachement

		/**
		 * update_post
		 *
		 * Clear cache according a post
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 * @param	int			$post_id		id the post
		 * @param	object		$post			post
		 * @param	int			$flag
		 * @return	none
		 *
		 */
		function update_post($post_id, $post ) {
			if ( 0 < $post_id ) {
				$this->clear_cache($post_id);
			}
		} // End of function update_post

		/**
		 * clear_cache
		 *
		 * Clear cache according a post
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 * @param	int			$post_id		id the post
		 * @param	object		$post			post
		 * @param	int			$flag
		 * @return	none
		 *
		 */
		function clear_cache($post_id) {
		
			$cache_entry = EG_Attachments_Common::get_cache_entry($this->name, $post_id );
			delete_transient($cache_entry);

		} // End of function clear_cache

		/**
		 * load
		 *
		 * Load the plugin
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function load() {
			parent::load();
			add_action('init', array(&$this, 'init'));

			/* --- Added in 2.0.1 --- */
			add_filter('sanitize_file_name', 'remove_accents');

			/* --- Added in 2.0.1 --- */
			add_filter( 'add_attachment',    array( $this, 'update_attachement' ) );
			add_filter( 'edit_attachment',   array( $this, 'update_attachement' ) );
			add_filter( 'delete_attachment', array( $this, 'update_attachement' ) );
			add_action( 'save_post', 		 array( $this, 'update_post' ), 10, 2 );
		} // End of load
		
	} // End of Class

} // End of if class_exists

$eg_attach_admin = new EG_Attachments_Admin(
							'EG-Attachments',
							EGA_VERSION,
							EGA_OPTIONS_ENTRY,
							EGA_TEXTDOMAIN,
							EGA_COREFILE,
							$EGA_DEFAULT_OPTIONS);

$eg_attach_admin->add_options_page(EGA_OPTIONS_PAGE_ID, 'EG-Attachments Settings', 'eg-attachments-settings.inc.php');
$eg_attach_admin->add_pointers(
		array('index.php' 				=> array('ega_stats', 'ega_templates'),
			'settings_page_ega_options' => array('ega_tinymce_button', 'ega_exclude_featured'),
			'tools_page_ega_templates'	=> 'ega_template_keywords'
		),
		array('ega_templates' 			=> array(EGA_READ_TEMPLATES, EGA_EDIT_TEMPLATES),
			'ega_tinymce_button' 		=> array('manage_options'),
			'ega_exclude_featured' 		=> array('manage_options'),
			'ega_stats'					=> array(EGA_VIEW_STATS),
			'ega_template_keywords'		=> array(EGA_EDIT_TEMPLATES)
		)
	);
$eg_attach_admin->load();

?>