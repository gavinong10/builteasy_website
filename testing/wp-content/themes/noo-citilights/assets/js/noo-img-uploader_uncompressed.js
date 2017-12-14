jQuery(document).ready(function ($) {
    var NOO_AIIU_Upload = {
        init:function () {
            window.nooImgUploadCount = $('#uploaded-images .uploaded-img').length;
            this.maxFiles = parseInt(noo_img_upload.max_files);

            $('#uploaded-images').on('click', 'a.remove-img', this.removeUploads);
            $('#uploaded-images').on('click', 'img', this.setFeatured);

            this.attach();
            this.hideUploader();
        },
        attach:function () {
            // wordpress plupload if not found
            if (typeof(plupload) === 'undefined') {
                return;
            }

            var uploader = new plupload.Uploader(noo_img_upload.plupload);

            $('#aaiu-uploader').click(function (e) {
                uploader.start();
                // To prevent default behavior of a tag
                e.preventDefault();
            });

            //initilize  wp plupload
            uploader.init();

            uploader.bind('FilesAdded', function (up, files) {
                $.each(files, function (i, file) {
                    $('#aaiu-upload-imagelist').append(
                        '<div id="' + file.id + '">' +
                            file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                            '</div>');
                });

                up.refresh(); // Reposition Flash/Silverlight
                uploader.start();
            });

            uploader.bind('UploadProgress', function (up, file) {
                $('#' + file.id + " b").html(file.percent + "%");
            });

            // On erro occur
            uploader.bind('Error', function (up, err) {
                $('#aaiu-upload-imagelist').append("<div>Error: " + err.code +
                    ", Message: " + err.message +
                    (err.file ? ", File: " + err.file.name : "") +
                    "</div>"
                );

                up.refresh(); // Reposition Flash/Silverlight
            });

            uploader.bind('FileUploaded', function (up, file, response) {
                var result = $.parseJSON(response.response);
                $('#' + file.id).remove();
                if (result.success) {
                    window.nooImgUploadCount += 1;

                    if( $('#gallery').length > 0 ) {
                        var gallery = $('#gallery').val();
                        gallery = ( gallery === '' ) ? result.image_id : gallery + ',' + result.image_id;
                        $('#gallery').val( gallery );
                        $('#uploaded-images').append('<div class="uploaded-img" data-imageid="'+result.image_id+'"><img src="'+result.thumbnail+'" /><a href="javascript:void(0)" class="remove-img"><i class="remove-img fa fa-trash-o"></i></a></div>');
                        if( $('#uploaded-images .featured-img').length === 0 ) {
                            var $first_img = $('#uploaded-images .uploaded-img:first-child');
                            $first_img.append('<i class="featured-img fa fa-star"></i>');
                            $('input#featured_img').val( $first_img.attr('data-imageid') )
                        }
                    }

                    if( $('#avatar').length > 0 ) {
                        $('#avatar').val(result.image_id);
                        $('#uploaded-images')
                            .empty()
                            .append('<div class="uploaded-img" data-imageid="'+result.image_id+'"><img src="'+result.thumbnail+'" /></div>');

                        $('.user-sidebar-menu .user-avatar > img').attr('src', result.image);
                    }

                    NOO_AIIU_Upload.hideUploader();
                }
            });


        },

        hideUploader:function () {

            if (NOO_AIIU_Upload.maxFiles !== 0 && window.nooImgUploadCount >= NOO_AIIU_Upload.maxFiles) {
                $('#aaiu-uploader').hide();
            }
        },

        removeUploads:function (e) {
            e.preventDefault();

            if (confirm(noo_img_upload.confirmMsg)) {

                var el = $(this),
                    data = {
                        'attach_id':el.parent().attr('data-imageid'),
                        'nonce':noo_img_upload.remove,
                        'action':'noo_delete_file'
                    };

                $.post(noo_img_upload.ajaxurl, data, function (response) {
                    el.parent().remove();

                    window.nooImgUploadCount -= 1;
                    if (NOO_AIIU_Upload.maxFiles !== 0 && window.nooImgUploadCount < NOO_AIIU_Upload.maxFiles) {
                        $('#aaiu-uploader').show();
                    }
                });
            }
        },

        setFeatured:function () {
            $('#uploaded-images .featured-img').each(function(){
                $(this).remove();
            });

            $(this).parent().append('<i class="featured-img fa fa-star"></i>');
            $('input#featured_img').val( $(this).parent().attr('data-imageid') );
        },

    };

    NOO_AIIU_Upload.init();
});