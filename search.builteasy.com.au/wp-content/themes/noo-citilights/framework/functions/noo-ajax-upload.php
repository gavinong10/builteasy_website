<?php

function noo_upload_form($images = '', $featured_img = '', $is_gallery = false) {
	?>
	<div id="aaiu-upload-imagelist">
		<ul id="aaiu-ul-list" class="aaiu-upload-list"></ul>
	</div>
	<div id="uploaded-images">
		<?php
		$gallery_value = array();
		if( !empty($featured_img) ) :
			$featured_img_src = wp_get_attachment_image_src($featured_img, 'property-thumbnail');
			if( $featured_img_src ) :
				$gallery_value[] = $featured_img;
			?>
		<div class="uploaded-img" data-imageid="<?php echo $featured_img; ?>">
			<img class="" src="<?php echo $featured_img_src[0]; ?>"/>
			<a href="javascript:void(0)" class="remove-img">
				<i class="action-remove fa fa-trash-o"></i>
			</a>
			<i class="featured-img fa fa-star"></i>
		</div>
		<?php
			endif;
		endif;

		if(!empty($images)) :
			$images_arr = explode(',', $images);
		foreach ($images_arr as $img_id) :
			$img_id = trim($img_id);
			if( empty($img_id) ) continue;
			$img_src = wp_get_attachment_image_src($img_id, 'property-thumbnail');
			if( !$img_src ) continue;
			$gallery_value[] = $img_id;
		?>
			<div class="uploaded-img" data-imageid="<?php echo $img_id; ?>">
				<img class="" src="<?php echo $img_src[0]; ?>">
				<?php if( $is_gallery ) : ?>
				<a href="javascript:void(0)" class="remove-img">
					<i class="remove-img fa fa-trash-o"></i>
				</a>
				<?php endif; ?>
			</div>
			<?php
			endforeach;
		endif;
		?>
	</div>
	<?php

	return implode(',', $gallery_value);
}

function noo_upload()
{
	check_ajax_referer('aaiu_allow', 'nonce');

	$file = array(
		'name'     => $_FILES['aaiu_upload_file']['name'],
		'type'     => $_FILES['aaiu_upload_file']['type'],
		'tmp_name' => $_FILES['aaiu_upload_file']['tmp_name'],
		'error'    => $_FILES['aaiu_upload_file']['error'],
		'size'     => $_FILES['aaiu_upload_file']['size']
		);
	$file = noo_fileupload_process($file);
}

function noo_fileupload_process($file)
{
	$attachment = noo_handle_file($file);

	if (is_array($attachment)) {
		$file = explode('/', $attachment['data']['file']);
		$file = array_slice($file, 0, count($file) - 1);
		$path = implode('/', $file);
		
		$dir = wp_upload_dir();
		$path = $dir['baseurl'] . '/' . $path;
		$thumbnail = '';
		$image = '';

		if( isset( $attachment['data']['sizes']['property-infobox'] ) ) {
			$thumbnail = $path . '/' . $attachment['data']['sizes']['property-infobox']['file'];
		} else {
			$thumbnail = $dir['baseurl'] . '/' . $attachment['data']['file'];
		}

		if( isset( $attachment['data']['sizes']['property-image'] ) ) {
			$image = $path . '/' . $attachment['data']['sizes']['property-image']['file'];
		} else {
			$image = $dir['baseurl'] . '/' . $attachment['data']['file'];
		}

		$response = array(
			'success'   => true,
			'image'     => $image,
			'thumbnail' => $thumbnail,
			'image_id'  => $attachment['id'],
		);

		echo json_encode($response);
		exit;
	}

	$response = array('success' => false);
	echo json_encode($response);
	exit;
}

function noo_handle_file($upload_data)
{

	$return = false;
	$uploaded_file = wp_handle_upload($upload_data, array('test_form' => false));

	if (isset($uploaded_file['file'])) {
		$file_loc = $uploaded_file['file'];
		$file_name = basename($upload_data['name']);
		$file_type = wp_check_filetype($file_name);

		$attachment = array(
			'post_mime_type' => $file_type['type'],
			'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file_name)),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		$attach_id = wp_insert_attachment($attachment, $file_loc);
		$attach_data = wp_generate_attachment_metadata($attach_id, $file_loc);
		wp_update_attachment_metadata($attach_id, $attach_data);

		$return = array('data' => $attach_data, 'id' => $attach_id);

		return $return;
	}

	return $return;
}

function noo_getHTML($attachment)
{
	$file = explode('/', $attachment['data']['file']);
	$file = array_slice($file, 0, count($file) - 1);
	$path = implode('/', $file);

	$dir = wp_upload_dir();
	$path = $dir['baseurl'] . '/' . $path;

	if( is_page_template('agent_dashboard_submit.php') ) {
		$image = $attachment['data']['sizes']['property-thumbnail']['file'] . 3;
	} else {
		$image = $attachment['data']['sizes']['property-image']['file'] . get_page_template();
	}

	$html = $path . '/' . $image;

	return $html;
}

function noo_delete_file()
{
	check_ajax_referer('aaiu_remove', 'nonce');

	$attach_id = $_POST['attach_id'];

	wp_delete_attachment($attach_id, true);
	exit;
}

add_action('wp_ajax_noo_upload', 'noo_upload');
add_action('wp_ajax_noo_delete_file', 'noo_delete_file');
add_action('wp_ajax_nopriv_noo_upload', 'noo_upload');
add_action('wp_ajax_nopriv_noo_delete_file', 'noo_delete_file');



