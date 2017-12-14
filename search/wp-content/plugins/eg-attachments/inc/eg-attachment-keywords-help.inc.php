<h3>
<?php esc_html_e('General help', $this->textdomain); ?>
</h3>
<p>
	<?php _e('Fields with <font color="red">red border</font> are mandatory', $this->textdomain); ?>
</p>
<h3>
<?php esc_html_e('Available keywords', $this->textdomain); ?>
</h3>
<h4>
<?php esc_html_e('Labels:', $this->textdomain); ?>
</h4>
<table class="eg-attach-custom-format">
<tbody>
	<tr>
		<td><strong>%TITLE_LABEL%</strong></td>
		<td><?php esc_html_e('Label for title (title in english, titre in french, Titolo in italian, ...', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%CAPTION_LABEL%</strong></td>
		<td><?php esc_html_e('Label for caption (caption in english)', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%DESCRIPTION_LABEL%</strong></td>
		<td><?php esc_html_e('Label for description (description in english)', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%EXT_LABEL%</strong></td>
		<td><?php esc_html_e('Label for file extension', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%FILENAME_LABEL%</strong></td>
		<td><?php esc_html_e('Label for filename (filename in english)', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%FILESIZE_LABEL%</strong></td>
		<td><?php esc_html_e('Label for the size of the file (<strong>size</strong> in english)', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%MIME_LABEL%</strong></td>
		<td><?php esc_html_e('Label for the mime type of the file', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%TYPE_LABEL%</strong></td>
		<td><?php esc_html_e('Label for the type of the file', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%DATE_LABEL%</strong></td>
		<td><?php esc_html_e('Label for the date', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%COUNTER_LABEL%</strong></td>
		<td><?php esc_html_e('Label of the counter (number of downloads of the document)', $this->textdomain); ?>,</td>
	</tr>
</tbody>
</table>
<h4>
<?php esc_html_e('Values:', $this->textdomain); ?>
</h4>
<table class="eg-attach-custom-format">
<tbody>
	<tr>
		<td><strong>%ATTID%</strong></td>
		<td><?php esc_html_e('ID of the attachment', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%CAPTION%</strong></td>
		<td><?php esc_html_e('caption of the document', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%COUNTER%</strong>(1)</td>
		<td><?php esc_html_e('Number of downloads recorded on the document.', $this->textdomain); ?></td>
	</tr>
	<tr>
		<td><strong>%DATE%</strong></td>
		<td><?php esc_html_e('Date of last modification of the file', $this->textdomain); ?></td>
	</tr>
	<tr>
		<td><strong>%DESCRIPTION%</strong></td>
		<td><?php esc_html_e('Description of the document', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%DIRECT_URL%</strong></td>
		<td><?php esc_html_e('Specific URL, pointing directly to the file, but without showing the file name and path (recommended)', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%FILENAME%</strong></td>
		<td><?php esc_html_e('Name of the attached file', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%EXT%</strong></td>
		<td><?php esc_html_e('Extension of the attached file', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%FILESIZE%</strong></td>
		<td><?php esc_html_e('Size of the attached document', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%FILE_URL%</strong></td>
		<td><?php esc_html_e('Direct link (url) to the document (media file).The URL is the document\'s address. For example: <code>http://blog url/wp-content/upload/2011/09/file name.file extension/</code>', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%GUID%</strong></td>
		<td><?php esc_html_e('Link (url) to document as stored in the WordPress database', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%ICON-<em>WW</em>x<em>HH</em>%</strong></td>
		<td><?php esc_html_e('Full icon tags (including A and IMG tag. <code>WW</code> is the width of the icon, and <code>HH</code> is the heigth.', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%ICONURL%</strong></td>
		<td><?php esc_html_e('URL of icon', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%LINK_URL%</strong></td>
		<td><?php _e('The URL of the attachment, as defined by the permalink structure chosen. For example: <code>http://blog url/post url/attachment slug/</code>, or <code>http://blog url/?p=xx</code>.', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%MIME%</strong></td>
		<td><?php esc_html_e('Mime type of the documents', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%NOFOLLOW%</strong>(2)</td>
		<td><?php _e('HTML attribut <code>rel="nofollow"</code>', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%POSTDATE%</strong></td>
		<td><?php esc_html_e('Date of last modification of the attachment', $this->textdomain); ?></td>
	</tr>	
	<tr>
		<td><strong>%SHOWLOCK%</strong></td>
		<td><?php esc_html_e('Show the lock icon for documents that require login or password', $this->textdomain); ?></td>
	</tr>
	<tr>
		<td><strong>%TARGET%</strong>(3)</td>
		<td><?php _e('HTML attribut <code>target="_blank"</code>', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%TARGET= ...%</strong>(3)</td>
		<td><?php _e('HTML attribut <code>target="value"</code>', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%TITLE%</strong></td>
		<td><?php esc_html_e('Title of the document', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%TYPE%</strong></td>
		<td><?php esc_html_e('Type of the documents (image, document, ...)', $this->textdomain); ?>,</td>
	</tr>
	<tr>
		<td><strong>%URL%</strong></td>
		<td><?php esc_html_e('Same than %LINK_URL%, %FILE_URL%, or %DIRECT_URL%, according the option chosen in the Settings/Options menu.', $this->textdomain); ?>,</td>
	</tr>
</tbody>
</table>
<ol>
	<li><?php esc_html_e('The number of downloads is displayed only when if the attachment was downloaded at least 1 time', $this->textdomain); ?>,</li>
	<li><?php echo sprintf(__('The &laquo; nofollow &raquo; attribut is displayed only if the option &laquo; nofollow &raquo; is checked in the <a href="%s">Settings / EG-Attachments</a> menu', $this->textdomain), admin_url('options-general.php?page='.EGA_OPTIONS_PAGE_ID)); ?>,</li>
	<li><?php echo sprintf(__('The &laquo; target &raquo; attributs are displayed only if the option &laquo; target &raquo; is checked in the <a href="%s">Settings / EG-Attachments</a> menu', $this->textdomain), admin_url('options-general.php?page='.EGA_OPTIONS_PAGE_ID)); ?>,</li>
</ol>
