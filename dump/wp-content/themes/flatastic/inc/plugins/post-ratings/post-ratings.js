
jQuery(document).ready(function($){

  // @todo: use $.on in future versions (needs jQuery 1.7+)
  $(document).delegate('.ratings:not(.rated) a', 'click', function(event){

    event.preventDefault();

    var control = $(this).parents('.ratings');

    $.ajax({
      url: (typeof atom_config !== 'undefined') ? atom_config.blog_url :  post_ratings.blog_url,
      type: 'GET',
      dataType: 'json',
      context: this,

      data: ({
        post_id: control.data('post'),
        rate: $(this).parents('li').index()
      }),

      beforeSend: function(){
        control.removeClass('error').addClass('loading');
      },

      error: function(){
        control.addClass('error');
      },

      success: function(response){

        // we have an error, display it
        if(response.error){
          control.addClass('error').find('.meta').html(response.error);
          $('a', control).remove();
          return;
        }

        // no error, replace the control html with the new one
        control.replaceWith($(response.html));

        // other plugins can hook into this event.
        // (the response object contains more info than we used here)
        control.trigger('rated_post', response);
      }
    });

    return true;

  });

});
