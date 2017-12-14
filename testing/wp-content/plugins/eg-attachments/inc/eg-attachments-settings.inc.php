<?php

global $EGA_FIELDS_ORDER_LABEL;
global $post;

// Build the list of post_types
$ega_post_type_list = array();
if (function_exists('get_post_types')) {
$all_post_types = get_post_types( array('public' => true, 'publicly_queryable' => true), 'object');
$exclusion_list    = array('post', 'page', 'attachment');
foreach ($all_post_types as $key => $post_type) {
if (! in_array($key, $exclusion_list)) {
$ega_post_type_list[$key] = $post_type->labels->name;
}
} // End foreach
} // End of function get_post_types exists

// Load list of existing templates
$templates_list = EG_Attachments_Common::get_templates($this->options, 'all', FALSE);

// Load list of existing icons sets
$icon_set_list  = EG_Attachments_Common::get_icon_set_list($this->textdomain);

// If the current icon set doesn't exist in the list, go back to the default one
// This is to prevent issues with icons plugins
EG_Attachments_Common::check_icons_set($icon_set_list, $this->options);

$tabs = array(
1	=> array( 'label' => 'Shortcodes behavior',	'header' => ''),
2 	=> array( 'label' => 'Auto shortcode',		'header' => ''),
3 	=> array( 'label' => 'Admin interface', 	'header' => '')
);

$sections = array(
'link'		=> array( 'label' => 'Link', 'tab' => 1, 'header' => '', 'footer' => ''),
'behavior'	=> array( 'label' => 'Behavior',
'tab' => 1,
'header' => 'CAUTION: the parameters of this section are impacting the default behavior of the shortcode. For example: if you activate &laquo; Nofollow &raquo; here, this attribut will be automatically add to the shortcode, evenif you don\'t specify it. If you don\'t check the option &laquo; Nofollow &raquo;, the attribut will appear only if you put the parameter nofollow=1 in the shortcode.',
'footer' => ''
),
'stat' 		=> array( 'label' => 'Statistics', 			'tab' => 1, 	'header' => '', 'footer' => ''),
'paths' 	=> array( 'label' => 'Icons set',			
'tab' => 1, 	
'header' => 'In this section, you can <strong>Choose a set of icons</strong>, or <strong>give a path, and an url</strong> where you put your own icons.</li></ul><br/>', 
'footer' => sprintf(__('<strong>Notes</strong><br /><ol><li>Icons sets are coming from EG-Attachments, but can also be provided by other plugins. An example of this kind of plugin is <a href="%1s" title="%2s">%3s</a>.<br />If you want to add a new icons set to the EG-Attachments plugin, please read the source code of the plugin <strong>%4s</strong>,</li><li>The icon set selection is active only if nothing is specified in the fieds <em>Icon path</em>, and <em>Icon url</em>.</li><li>The fieds <em>Icon path</em>, and <em>Icon url</em> are working only for file types, not for file extensions. For example, if you have two icons in the path specified: one for the type <em>document</em>, the other for the file extension <em>pdf</em>, then a PDF file will be displayed with the icon <em>document</em>.</li><li>The file types are the "WordPress file types": <em>%5s</em>.</li></ol>', $this->textdomain), 'http://wordpress.org/plugins/eg-attachments-flat-icons', __('EG-Attachments Flat Icons in the WordPress plugin repository', $this->textdomain), 'EG-Attachments Flat Icons', 'EG-Attachments Flat Icons', 'image, audio, video, document, spreadsheet, interactive, text, archive, code')
),
'security'	=> array( 'label' => 'Security',
'tab' => 1,
'header' => 'Some comments about security:'.
'<ul>'.
'<li>- &laquo;Private&raquo; Attachments, or documents attached to a &laquo;Private&raquo; post, are not displayed, if the user is not logged,</li>'.
'<li>- &laquo;Protected&raquo; Attachments, or documents attached to a &laquo;Protected&raquo; post, are not displayed, if users don\'t give the right password,</li>'.
'<li>- For non private, and non protected posts, you can choose with the following options, to display attachments, to display without link, or hide attachments.</li>'.
'</ul>'
,
'footer' => 'CAUTION: the parameter <strong>Attachments access</strong> is impacting the default behavior of the shortcode.'
),
'sformat'	=> array( 'label' => 'Format', 	 			'tab' => 1, 	'header' => '', 'footer' => ''),
'cformat'	=> array( 'label' => 'Custom format',		'tab' => 1, 	'header' => '', 'footer' => ''),
'auto' 		=> array( 'label' => 'Activation', 			'tab' => 2,		'header' => '', 'footer' => ''),
'asformat' 	=> array( 'label' => 'Format', 				'tab' => 2,		'header' => '', 'footer' => ''),
'asbehavior'=> array( 'label' => 'Behavior', 			'tab' => 2,		'header' => '', 'footer' => ''),
'admin_bar'	=> array( 'label' => 'Administration bar', 	'tab' => 3, 	'header' => '', 'footer' => ''),
'editor'	=> array( 'label' => 'Post editor page', 	'tab' => 3,		'header' => '', 'footer' => ''),
'styles' 	=> array( 'label' => 'Styles', 				'tab' => 3, 	'header' => '', 'footer' => ''),
'uninstall' => array( 'label' => 'Uninstallation', 		'tab' => 3, 	'header' => '', 'footer' => 'Be careful: these actions cannot be cancelled.')
);

$fields = array(
'link' => array(
'name'		=> 'link',
'label'		=> 'Links to attachments',
'type'		=> 'radio',
'section'	=> 'link',
'group'		=> 0,
'before'	=> '',
'after' => '<br />All features cannot be implemented with all of these modes.<br />'.
'<table class="eg-attach-help">'.
'<tr>'.
'<th>Link type</th><th>Statistics</th>'.
'<th>Force "Save as"</th>'.
'<th>Security</th>'.
'</tr>'.
'<tr>'.
'<th class="row">Permalink</th>'.
'<td>x</td>'.
'<td>x</td>'.
'<td>x</td>'.
'</tr>'.
'<tr>'.
'<th class="row">File</th>'.
'<td>&nbsp;</td>'.
'<td>&nbsp;</td>'.
'<td>&nbsp;</td>'.
'</tr>'.
'<tr>'.
'<th class="row">Direct</th>'.
'<td>x</td>'.
'<td>x</td>'.
'<td>x</td>'.
'</tr>'.
'</table>',
'desc'		=> '',
'options'	=> array(
'direct'    => '<strong>Direct</strong>.<br /><em>It\'s the default behavior of the plugin. The URL is pointing to the file, but in this case, the link is encoded. When you click on the attachment, it is displayed as a document, and not as a page of blog. WordPress mechanisms are used</em>',
'link' => '<strong>Permalink</strong>.<br /><em>The URL of the attachment is using defined permalink structure. For example: <code>http://blog url/post url/attachment slug/</code>, or <code>http://blog url/?p=xx</code>.<br/>The attachment will be displayed inside your blog, as a post.</em>',
'file' => '<strong>File</strong>.<br /><em>The URL is the document\'s address. For example: <code>http://blog url/wp-content/upload/2011/09/file name.file extension/</code>.<br />When you click on the attachment, it is displayed directly, as a document, and not as a page of blog. WordPress mechanisms are not used</em>'),
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'force_saveas' => array(
'name'		=> 'force_saveas',
'label'		=> '"Save As" activation',
'type'		=> 'checkbox',
'section'	=> 'behavior',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> 'In normal mode, when you click on the attachments\' links, according their mime type, documents are displayed, or a dialog box appears to choose \'run with\' or \'Save As\'. By activating the following option, the dialog box will appear for all cases.',
'options'	=> array('Force "Save As" when users click on the attachments'),
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),

'icon_image' => array(
'name'		=> 'icon_image',
'label'		=> 'Icon for the images',
'type'		=> 'radio',
'section'	=> 'behavior',
'group'		=> 0,
'before'	=> 'When a list of attachments includes images, do you want to display',
'after'		=> '',
'desc'		=> 'The thumbnail will be displayed with the size specified in the template',
'options'	=> array( 'icon' => 'The icon of the file type', 'thumbnail' => 'The Thumbnail of the image'),
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),

'nofollow' => array(
'name'		=> 'nofollow',
'label'		=> '&laquo;Nofollow&raquo; attribute',
'type'		=> 'checkbox',
'section'	=> 'behavior',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> array( 'Check if you want to automatically add <code>rel="nofollow"</code> to attachment links' ),
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),

'nofollow' => array(
'name'		=> 'nofollow',
'label'		=> '&laquo;Nofollow&raquo; attribute',
'type'		=> 'checkbox',
'section'	=> 'behavior',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> array( 'Check if you want to automatically add <code>rel="nofollow"</code> to attachment links' ),
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),

'target_blank' => array(
'name'		=> 'target_blank',
'label'		=> '&laquo;target=blank&raquo; attribute',
'type'		=> 'checkbox',
'section'	=> 'behavior',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> array( 'Check if you want to automatically add <code>target="_blank"</code> to attachment links' ),
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),

'exclude_thumbnail' => array(
'name'		=> 'exclude_thumbnail',
'label'		=> 'Featured image',
'type'		=> 'checkbox',
'section'	=> 'behavior',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> array( 'Check if you want to exclude featured image (thumbnail) from the list of attachments' ),
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),

'stats_enable' => array(
'name'		=> 'stats_enable',
'label'		=> 'Click counter',
'type'		=> 'checkbox',
'section'	=> 'stat',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> 'Record all clicks occurring in the listed attachments.',
'options'	=> array('Activate statistics'),
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'stats_ip_exclude' => array(
'name'		=> 'stats_ip_exclude',
'label'		=> 'Statistics, IP to exclude',
'type'		=> 'text',
'section'	=> 'stat',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> 'List of IP address you want to exclude',
'options'	=> FALSE,
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),

'purge_stats' => array(
'name'		=> 'purge_stats',
'label'		=> 'Maintenance',
'type'		=> 'text',
'section'	=> 'stat',
'group'		=> 0,
'before'	=> 'Delete records after',
'after'		=> 'months',
'desc'		=> 'Enter 0 (zero), if you wand to disable the maintenance',
'options'	=> FALSE,
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),


'logged_users_only' => array(
'name'		=> 'logged_users_only',
'label'		=> 'Attachments access',
'type'		=> 'radio',
'section'	=> 'security',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> array( 0 => 'Display attachments for all users', 
1 => 'Show attachments for everyone, but the URL, for logged users only',
2 => 'Display attachments for logged users only'
),
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),

'login_url' => array(
'name'		=> 'login_url',
'label'		=> 'URL to login or register page',
'type'		=> 'text',
'section'	=> 'security',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> sprintf(__('If you leave the field empty, the usual WordPress login form will be used (<code>%s</code>).', $this->textdomain), wp_login_url() ),
'options'	=> FALSE,
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),

'date_format' => array(
'name'		=> 'date_format',
'label'		=> 'Date format',
'type'		=> 'text',
'section'	=> 'sformat',
'group'		=> 0,
'before'	=> '',
'after'		=> sprintf(__('Default value: %s (%s)', $this->textdomain), get_option('date_format'), date_i18n(get_option('date_format'))),
'desc'		=> 'Use PHP <strong>date()</strong> parameters to configure the date:<br />d&nbsp;&nbsp;&nbsp;&nbsp;Day of the month, 2 digits with leading zeros<br />D&nbsp;&nbsp;&nbsp;&nbsp;A textual representation of a day, three letters<br />j&nbsp;&nbsp;&nbsp;&nbsp;Day of the month without leading zeros<br />S&nbsp;&nbsp;&nbsp;&nbsp;English ordinal suffix for the day of the month, 2 characters<br />F&nbsp;&nbsp;&nbsp;&nbsp;A full textual representation of a month, such as January or March<br />m&nbsp;&nbsp;&nbsp;&nbsp;Numeric representation of a month, with leading zeros<br />M&nbsp;&nbsp;&nbsp;&nbsp;A short textual representation of a month, three letters<br />n&nbsp;&nbsp;&nbsp;&nbsp;Numeric representation of a month, without leading zeros<br />Y&nbsp;&nbsp;&nbsp;&nbsp;A full numeric representation of a year, 4 digits<br />y&nbsp;&nbsp;&nbsp;&nbsp;A two digit representation of a year',
'options'	=> FALSE,
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'custom_format_obsolete' => array(
'name'		=> 'custom_format_obsolete',
'label'		=> 'Custom format',
'type'		=> 'comment',
'section'	=> 'cformat',
'group'		=> 0,
'before'	=> '<strong>Custom format has been changed</strong>.<br />To make the custom formats more flexible, they are now manage in a specific menu. You can go to the menu <strong>Tools &gt; EGA Templates</strong> to discover the new way of managing them. During the update, your custom format were saved into a template.',
'after'		=> '',
'desc'		=> '',
'options'	=> FALSE,
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),


'shortcode_auto' => array(
'name'		=> 'shortcode_auto',
'label'		=> 'Activation',
'type'		=> 'select',
'section'	=> 'auto',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> 'With this option, you can automatically add the list of attachments in your blog, without using shortcode',
'options'	=> array( 0 => 'Not activated', 2 => 'At the end', 3 => 'Before the excerpt', 4 => 'Between excerpt and content'),
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'shortcode_auto_exclusive' => array(
'name'		=> 'shortcode_auto_exclusive',
'label'		=> 'Auto / Manual',
'type'		=> 'checkbox',
'section'	=> 'auto',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> 'If this option is activated, auto shortcode won\'t be generated if a manual shortcode is detected in the post being displayed',
'options'	=> array('Disable auto-shortcode when a manual shortcode is detected'),
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'shortcode_auto_where' => array(
'name'		=> 'shortcode_auto_where',
'label'		=> 'Where',
'type'		=> 'checkbox',
'section'	=> 'auto',
'group'		=> 0,
'before'	=> 'Activate automatic shortcode only on the following cases',
'after'		=> '',
'desc'		=> 'If this option is activated, auto shortcode won\'t be generated if a manual shortcode is detected in the post being displayed',
'options'	=> array_merge(array( 'home' => 'Homepage,', 'post' => 'Posts,', 'page' => 'Pages,', 'index' => 'Lists of posts (archives, categories, ...).'),$ega_post_type_list),
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'shortcode_auto_title' => array(
'name'		=> 'shortcode_auto_title',
'label'		=> 'Title of the list',
'type'		=> 'text',
'section'	=> 'asformat',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> FALSE,
'size'		=> 'medium',
'status'	=> '',
'multiple'	=> FALSE),

'shortcode_auto_title_tag' => array(
'name'		=> 'shortcode_auto_title_tag',
'label'		=> 'HTML Tag for title',
'type'		=> 'text',
'section'	=> 'asformat',
'group'		=> 0,
'before'	=> '',
'after'		=> '(tag like h2, or h3, ...)',
'desc'		=> '',
'options'	=> FALSE,
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),
'shortcode_auto_template' => array(
'name'		=> 'shortcode_auto_template',
'label'		=> 'Custom format',
'type'		=> 'radio_table',
'section'	=> 'asformat',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> $templates_list,
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE,
'list_options'  => array(
'no_option'	=> 'No template available',
'titles'	=> array( 'Title', 'Slug', 'Description'),
'fields'	=> array( 'post_title', 'post_name', 'post_excerpt')
)
),
'shortcode_auto_doc_type' => array(
'name'		=> 'shortcode_auto_doc_type',
'label'		=> 'Document type',
'type'		=> 'radio',
'section'	=> 'asbehavior',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> array( 'all' => 'All', 'document' => 'Documents', 'image' => 'Images'),
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'shortcode_auto_orderby' => array(
'name'		=> 'shortcode_auto_orderby',
'label'		=> 'Order by',
'type'		=> 'select',
'section'	=> 'asbehavior',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> $EGA_FIELDS_ORDER_LABEL,
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'shortcode_auto_order' => array(
'name'		=> 'shortcode_auto_order',
'label'		=> 'Sort Order',
'type'		=> 'radio',
'section'	=> 'asbehavior',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> array('ASC' => 'Ascending', 'DESC' => 'Descending'),
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'shortcode_auto_limit' => array(
'name'		=> 'shortcode_auto_limit',
'label'		=> 'Number of documents to display',
'type'		=> 'text',
'section'	=> 'asbehavior',
'group'		=> 0,
'before'	=> '',
'after'		=> '(Default: -1, for all document are listed)',
'desc'		=> '',
'options'	=> FALSE,
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'shortcode_auto_default_opts' => array(
'name'		=> 'shortcode_auto_default_opts',
'label'		=> 'Default options?',
'type'		=> 'checkbox',
'section'	=> 'asbehavior',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> array('Do you want that auto shortcode options become the default options for the TinyMCE EG-Attachments Editor?'),
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'display_admin_bar' => array(
'name'		=> 'display_admin_bar',
'label'		=> 'Administration bar',
'type'		=> 'checkbox',
'section'	=> 'admin_bar',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> array( 'Display a <strong>EG-Attachments</strong> menu in the administration bar'),
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'use_metabox' => array(
'name'		=> 'use_metabox',
'label'		=> 'Metaboxes',
'type'		=> 'checkbox',
'section'	=> 'editor',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> array( 'Show metabox to display list of attachments of the current post/page'),
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'tinymce_button' => array(
'name'		=> 'tinymce_button',
'label'		=> 'EG-Attachments button',
'type'		=> 'checkbox',
'section'	=> 'editor',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> '',
'options'	=> array( 'Show EG-Attachments button in post text editor ?'),
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'tags_assignment' => array(
'name'		=> 'tags_assignment',
'label'		=> 'Tags assignments',
'type'		=> 'checkbox',
'section'	=> 'editor',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> 'If you select this option, you will be able to select attachments by using the <strong>tags</strong> parameter in the shortcode.',
'options'	=> array( 'Do you want to assign tags to attachments?'),
'size'		=> 'small',
'status'	=> '',
'multiple'	=> FALSE),

'icon_set'	=> array(
'name'		=> 'icon_set',
'label'		=> 'Icons set',
'type'		=> 'radio',
'section'	=> 'paths',
'group'		=> 0,
'before'	=> '',
'after' 	=> '',
'desc'		=> '',
'options'	=> $icon_set_list,
'size'		=> 'small',
'status'	=> ( 1 < sizeof( $icon_set_list ) ? '' : 'disabled'),
'multiple'	=> FALSE
),

'icon_path' => array(
'name'		=> 'icon_path',
'label'		=> 'Icon path',
'type'		=> 'text',
'section'	=> 'paths',
'group'		=> 0,
'before'	=> trailingslashit(wp_normalize_path( ABSPATH ) ),
'after'		=> '',
'desc'		=> 'Additional path where the plugin can get icons',
'options'	=> FALSE,
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),

'icon_url' => array(
'name'		=> 'icon_url',
'label'		=> 'Icon_url',
'type'		=> 'text',
'section'	=> 'paths',
'group'		=> 0,
'before'	=> trailingslashit(get_bloginfo('url')),
'after'		=> '',
'desc'		=> 'Additional URL where the plugin can get icons',
'options'	=> FALSE,
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),

'load_css' => array(
'name'		=> 'load_css',
'label'		=> 'Stylesheet',
'type'		=> 'checkbox',
'section'	=> 'styles',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> 'Check if you want to use the plugin stylesheet file, uncheck if you want to use your own styles, or include styles on the theme stylesheet.',
'options'	=> array('Automatically load plugins\' stylesheet'),
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),

'uninstall_del_options' => array(
'name'		=> 'uninstall_del_options',
'label'		=> 'Options',
'type'		=> 'checkbox',
'section'	=> 'uninstall',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> 'Plugin\'s data, including options, and templates will be deleted while plugin uninstallation.',
'options'	=> array('Delete options and templates, during uninstallation.'),
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE),

'uninstall_del_stats' => array(
'name'		=> 'uninstall_del_stats',
'label'		=> 'Data',
'type'		=> 'checkbox',
'section'	=> 'uninstall',
'group'		=> 0,
'before'	=> '',
'after'		=> '',
'desc'		=> 'All statistics about downloads / views of attached files, will be deleted while plugin uninstallation.',
'options'	=> array('Delete statistics during uninstallation.'),
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> FALSE)

);

$clear_cache_options = EG_Attachments_Common::list_of_cache($this->name, $this->textdomain);
if ( FALSE !== $clear_cache_options && is_array($clear_cache_options) ) {

/* --- Clear the option --- */
$this->options['clear_cache'] = FALSE;
update_option($this->options_entry, $this->options);

$tabs[4] = array(
'label' => 'Cache',
'header' => ''
);

$sections['cache'] = array(
'label' => 'Clear plugin cache',
'tab' => 4,
'header' => 'The plugin is using cache to store the list of attachments. If the changes you make in the administration interface, are not seen in the front-end, you can use this option to clear the cache',
'footer' => ''
);

$fields['clear_cache'] = array(
'name'		=> 'clear_cache',
'label'		=> 'Clear cache entries',
'type'		=> 'checkbox',
'section'	=> 'cache',
'group'		=> 0,
'before'	=> 'Check the options, if you want to delete the following cache entries:',
'after'		=> '',
'desc'		=> '',
'options'	=> $clear_cache_options,
'size'		=> 'regular',
'status'	=> '',
'multiple'	=> TRUE
);
} // End of cache not empty


$option_form->set_form($tabs, $sections, $fields);

?>