<?php


// if this an Atom theme make a special widget for it ;)
if(class_exists('AtomWidget') && (defined('Atom::VERSION'))){


 /*
  * Atom Top Rated Widget
  *
  * @since 1.0
  */
  class PostRatingsWidget extends AtomWidget{



   /*
    * Initialization
    *
    * @see AtomWidget::set and WP_Widget::__construct
    */
    public function __construct(){

      // register the widget and it's options
      $this->set(array(

        'id'           => 'top-rated',
        'title'        => __('Top Rated', PostRatings::ID),
        'description'  => __('The highest rated posts on your site', PostRatings::ID),
        'width'        => 500,
        'ajax_control' => 'get_top_rated',

        // default widget options
        'defaults'     => array(
          'title'         => __('Top Rated', PostRatings::ID),
          'sort'          => 'bayesian_rating',
          'order'         => 'DESC',
          'number'        => 5,
          'date_limit'    => 0,
          'post_type'     => 'post',
          'thumb_size'    => 48,
          'more'          => true,
        ),

        // one template definition only
        'templates'   => array(
          '<a class="clear-block" href="{URL}" title="{TITLE}">',
          ' {THUMBNAIL}',
          '  <span class="tt">{TITLE} ({AVG_RATING})</span>',
          '  <span class="c1">',
          ' {CONTENT}',
          ' {BAYESIAN_RATING_BAR}',
          '  </span>',
          '</a>',
        ),
      ));

      // register thumbnail size
      add_action('wp_loaded',    array(&$this, 'setThumbSize'));

      add_action('save_post',    array(&$this, 'flushCache'));
      add_action('deleted_post', array(&$this, 'flushCache'));
      add_action('switch_theme', array(&$this, 'flushCache'));
      add_action('rated_post',   array(&$this, 'flushCache'));
    }



    public function setThumbSize($sizes){

      // we need to process all instances because this function gets to run only once
      $widget_options = get_option($this->option_name);
      foreach((array)$widget_options as $instance => $options){

        // identify instance
        $id = "{$this->id_base}-{$instance}";

        // register thumb size if the widget is active
        if(!is_active_widget(false, $id, $this->id_base)) continue;

        $options = wp_parse_args($options, AtomWidget::getObject($id)->getDefaults());
        add_image_size($id, $options['thumb_size'], $options['thumb_size'], true);

      }
      return $sizes;
    }



    protected function getTopRated($args, &$more, $offset = 0){
      global $post;

      extract($args);

      $posts = PostRatings()->getTopRated(array(
        'post_type'  => $post_type,
        'number'     => $number,
        'sortby'     => $sort,
        'order'      => in_array($order, array('ASC', 'DESC'), true) ? $order : 'DESC',
        'offset'     => $offset,
        'date_limit' => $date_limit,
      ));

      $output = array();
      $max_rating = PostRatings()->getOptions('max_rating');

      foreach($posts as $index => $post){

        atom()->post = $post;
        $output[] = '<li>';

        $avg_rating = atom()->post->get('rating');
        $bayesian_rating = atom()->post->get('bayesian_rating');


        $bayesian_rating_bar = sprintf('<span class="rating" title="%s"><span class="bar" style="width:%d%%">%.2F</span></span>',
           sprintf(__('%s out of 10', PostRatings::ID), $avg_rating), (($bayesian_rating / $max_rating) * 100), $avg_rating);

        $avg_rating_bar = sprintf('<span class="rating" title="%s"><span class="bar" style="width:%d%%">%.2F</span></span>',
           sprintf(__('%s out of 10', PostRatings::ID), $avg_rating), (($avg_rating / $max_rating) * 100), $avg_rating);

        $fields = array(
          'TITLE'               => atom()->post->getTitle(),
          'COMMENT_COUNT'       => atom()->post->getCommentCount(),
          'THUMBNAIL'           => atom()->post->getThumbnail(str_replace('instance-', '', $id)),
          'URL'                 => atom()->post->getURL(),
          'CONTENT'             => convert_smilies(atom()->post->getContent(150, array(
                                     'allowed_tags' => Atom::SAFE_INLINE_TAGS,
                                     'more'         => '[&hellip;]',
                                   ))),
          'AVG_RATING'          => sprintf('%.2F', $avg_rating),
          'BAYESIAN_RATING'     => (int)(100 * ($bayesian_rating / $max_rating)),
          'BAYESIAN_RATING_BAR' => $bayesian_rating_bar,
          'AVG_RATING_BAR'      => $avg_rating_bar,
          'VOTES'               => (int)atom()->post->get('votes'),
          'MAX_RATING'          => $max_rating,
          'EXCERPT'             => atom()->post->getContent('e'),
          'DATE'                => atom()->post->getDate('relative'),
          'AUTHOR'              => atom()->post->author->getName(),
          'CATEGORIES'          => strip_tags(atom()->post->getTerms('category')),
          'TAGS'                => strip_tags(atom()->post->getTerms()),
          'VIEWS'               => number_format_i18n(atom()->post->getViews()),
          'INDEX'               => isset($posts->current_post) ? ($posts->current_post + 1) : $index,
          'ID'                  => atom()->post->getID(),
        );


        $fields = apply_filters('atom_widget_top_rated_keywords', $fields, atom()->post, $args);

        // output template
        $output[] = atom()->getBlockTemplate($template, $fields);

        $output[] = '</li>';
      }

      atom()->resetCurrentPost();

      return implode("\n", $output);
    }



    public function widget($args, $instance){
      extract($args);

      // check for a cached instance and display it if we have it
      if($this->getAndDisplayCache($widget_id)) return;

      $instance = wp_parse_args($instance, $this->getDefaults());
      $instance['id'] = $this->id;

      $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

      $posts = $this->getTopRated($instance, $next);

      if(empty($posts))
        return atom()->log("No rated posts found in {$args['widget_id']} ({$args['widget_name']}). Widget marked as inactive");

      $output = $before_widget;

      if($title)
        $output .= $before_title.$title.$after_title;

      $output .= "<ul class=\"menu fadeThis ratings clear-block\">\n{$posts}\n</ul>\n";

      if($instance['more'] && $next && atom()->options('jquery')){

        // Atom 2.0 r9 +
        if(method_exists($this, 'getMoreLink')){
          $output .= $this->getMoreLink($instance['number'], 'get_top_rated');

        // for compatibility (deprecated from Atom 2.0 r9)
        }else{
          $output .= '<div class="fadeThis clear-block"><a href="#" class="more" data-count="'.$instance['number'].'">'.__('Show More', PostRatings::ID).'</a></div>';
          $output .= $this->getShowMoreCtrl('get_top_rated');

        }

      }

      $output .= $after_widget;

      echo $output;

      $this->addCache($widget_id, $output);
    }



   /*
    * Saves the widget options
    *
    * @see WP_Widget::update
    */
    public function update($new_instance, $old_instance){

      $this->FlushCache();
      extract($new_instance);

      return array(
        'title'         => esc_attr($title),
        'sort'          => esc_attr($sort),
        'order'         => ($order !== 'DESC') ? 'ASC' : 'DESC',
        'number'        => min(max((int)$number, 1), 20),
        'date_limit'    => (int)$date_limit,
        'post_type'     => post_type_exists($post_type) ? $post_type : 'post',
        'thumb_size'    => (int)$thumb_size,
        'more'          => (bool)$more,
        'template'      => (isset($template) && current_user_can('edit_themes')) ? $template : $old_instance['template'],

      ) + $old_instance;

    }



    public function form($instance){
      $instance = wp_parse_args($instance, $this->getDefaults());
      $plugin_options = PostRatings()->getOptions();
      ?>
      <div <?php $this->formClass(); ?>>

        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', PostRatings::ID); ?></label>
          <input class="wide" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Get most rated:', PostRatings::ID); ?></label>
          <select id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>">
            <?php foreach(get_post_types(array('public' => true)) as $type): ?>
            <?php $object = get_post_type_object($type); ?>
            <option <?php if(!in_array($type, $plugin_options['post_types'])): ?> disabled="disabled" <?php endif; ?> value="<?php echo $type; ?>" <?php selected($type, $instance['post_type']); ?>><?php echo $object->labels->name; ?></option>
            <?php endforeach; ?>
          </select>
        </p>

        <p>
         <label for="<?php echo $this->get_field_id('sort'); ?>"><?php _e('Sort by:', PostRatings::ID); ?></label>
         <select class="wide" id="<?php echo $this->get_field_id('sort'); ?>" name="<?php echo $this->get_field_name('sort'); ?>">
           <option <?php selected('bayesian_rating', $instance['sort']); ?> value="bayesian_rating"><?php _e('Overall bayesian rating (score)', PostRatings::ID); ?></option>
           <option <?php selected('rating', $instance['sort']); ?> value="rating"><?php _e('Average rating', PostRatings::ID); ?></option>
           <option <?php selected('votes', $instance['sort']); ?> value="votes"><?php _e('Number of votes', PostRatings::ID); ?></option>
         </select>
        </p>

        <p>
         <label for="<?php echo $this->get_field_id('order'); ?>_desc">
           <input id="<?php echo $this->get_field_id('order'); ?>_desc" name="<?php echo $this->get_field_name('order'); ?>" type="radio" value="DESC" <?php checked('DESC', $instance['order']); ?> />
           <?php _e('Descending', PostRatings::ID); ?>
         </label>
         &nbsp;&nbsp;&nbsp;&nbsp;
         <label for="<?php echo $this->get_field_id('order'); ?>_asc">
           <input id="<?php echo $this->get_field_id('order'); ?>_asc" name="<?php echo $this->get_field_name('order'); ?>" type="radio" value="ASC" <?php checked('ASC', $instance['order']); ?> />
           <?php _e('Ascending', PostRatings::ID); ?>
         </label>
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('date_limit'); ?>"><?php _e('Ignore posts older than:', PostRatings::ID); ?></label>
          <input id="<?php echo $this->get_field_id('date_limit'); ?>" name="<?php echo $this->get_field_name('date_limit'); ?>" type="text" value="<?php echo $instance['date_limit']; ?>" size="3" /> <?php _e('days', PostRatings::ID); ?>
          <br />
          <small><?php _e('(0 to ignore date)', PostRatings::ID); ?></small>
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('thumb_size'); ?>"><?php _e('Thumbnail Size:', PostRatings::ID) ?></label>
          <input type="text" size="3" id="<?php echo $this->get_field_id('thumb_size'); ?>" name="<?php echo $this->get_field_name('thumb_size'); ?>" value="<?php if (isset($instance['thumb_size'])) echo (int)$instance['thumb_size']; ?>" /> <em><?php _e('pixels', PostRatings::ID); ?></em>
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Limit:', PostRatings::ID); ?></label>
          <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $instance['number']; ?>" size="3" />
        </p>

        <p>
         <input <?php if(!atom()->options('jquery')) echo "disabled=\"disabled\""; ?> type="checkbox" id="<?php echo $this->get_field_id('more'); ?>" name="<?php echo $this->get_field_name('more'); ?>"<?php checked($instance['more']); ?> />
         <label for="<?php echo $this->get_field_id('more'); ?>" <?php if(!atom()->options('jquery')) echo "class=\"disabled\""; ?>><?php printf(__('Display %s Link', PostRatings::ID), '<code>'.__('Show More', PostRatings::ID).'</code>'); ?></label>
        </p>

        <?php if(current_user_can('edit_themes')): ?>
        <div class="user-template">
          <textarea class="wide code editor" id="<?php echo $this->get_field_id('template'); ?>" name="<?php echo $this->get_field_name('template'); ?>" rows="8" cols="28" mode="atom/html"><?php echo (empty($instance['template'])) ? format_to_edit($this->getTemplate()) : format_to_edit($instance['template']); ?></textarea>
          <small>
            <?php printf(__('Read the %s to see all available keywords.', PostRatings::ID), '<a href="'.Atom::THEME_DOC_URI.'" target="_blank">'.__('documentation', PostRatings::ID).'</a>'); ?>
          </small>
        </div>
        <?php endif; ?>

        <hr />
        <p>
          <em>
            <?php
              printf(__('<strong>Important:</strong> %1$s sized thumbnails have to be created if you just added this widget, or if you\'re changing the thumbnail size. Read more about thumbnail sizes %2$s', PostRatings::ID),  (int)$instance['thumb_size'].'x'.(int)$instance['thumb_size'], '<a href="'.admin_url('themes.php?page='.ATOM.'#advanced').'">'.__('here', PostRatings::ID).'</a>'); ?>
          </em>
        </p>

      </div>
      <?php
    }
  }





// ...normal widget
}else{






 /*
  * Top Rated posts widget
  *
  * @since 1.0
  */
  class PostRatingsWidget extends WP_Widget{



   /*
    * @since   1.0
    * @see     WP_Widget::__construct
    */
    function __construct(){

      $widget_ops = array('classname' => 'widget_top_rated', 'description' => __('The highest rated posts on your site', PostRatings::ID));
      parent::__construct('top-rated', __('Top Rated', PostRatings::ID), $widget_ops);

      add_action('save_post',    array(&$this, 'flush_widget_cache'));
      add_action('deleted_post', array(&$this, 'flush_widget_cache'));
      add_action('switch_theme', array(&$this, 'flush_widget_cache'));
      add_action('rated_post',   array(&$this, 'flush_widget_cache'));

    }



   /*
    * @since   1.0
    * @see     WP_Widget::widget
    */
    public function widget($args, $instance){
      global $post;

      extract($args);

      $cache = wp_cache_get('widget_top_rated', 'widget');

      if(!is_array($cache))
        $cache = array();

      if(isset($cache[$widget_id])){
        echo $cache[$widget_id];
        return;
      }

      $output = array();
      $options = PostRatings()->getOptions();
      extract($options);

      $title      = apply_filters('widget_title', empty($instance['title']) ? __('Top Rated', PostRatings::ID) : $instance['title'], $instance, $this->id_base);
      $number     = min(max((int)$instance['number'], 1), 20);
      $date_limit = min(max((int)$instance['date_limit'], 0), 999);
      $post_type  = post_type_exists($instance['post_type']) ? $instance['post_type'] : 'post';
      $sort       = isset($instance['sort']) ? esc_attr($instance['sort']) : 'bayesian_rating';

      $output[] = $before_widget;

      if($title)
        $output[] = $before_title.$title.$after_title;

      $posts = PostRatings()->getTopRated(array(
        'post_type'  => $post_type,
        'number'     => $number,
        'sortby'     => $sort,
        'order'      => in_array($instance['order'], array('ASC', 'DESC'), true) ? $instance['order'] : 'DESC',
        'date_limit' => $date_limit,
      ));

      if($posts){
        $output[] = '<ul>';

        foreach($posts as $post){
          setup_postdata($post);
          $output[] = '<li>';
          $output[] = '<a href="'.get_permalink().'">'.get_the_title().'</a>';

          if($sort === 'votes')
            $output[] =  '('.sprintf(_n('%d vote', '%d votes', $post->votes, MAD_BASE_TEXTDOMAIN), $post->votes, $post->votes).')';

          elseif($sort === 'rating')
            $output[] = sprintf('(%.2F)', $post->rating);

          else
            $output[] = sprintf('(%d%%)', (100 * ($post->bayesian_rating / $max_rating)));


          $output[] = '</li>';
        }

        $output[] = '</ul>';
        wp_reset_postdata();
      }

      $output[] = $after_widget;

      $output = implode("\n", $output);

      echo $output;

      $cache[$widget_id] = $output;
      wp_cache_set('widget_top_rated', $cache, 'widget');
    }



   /*
    * @since   1.0
    * @see     WP_Widget::update
    */
    public function update($new_instance, $old_instance){

      extract($new_instance);

      $instance = $old_instance;

      $instance['title'] = esc_attr(strip_tags($title));
      $instance['sort'] = esc_attr(strip_tags($sort));
      $instance['order'] = in_array($order, array('ASC', 'DESC'), true) ? $order : 'DESC';
      $instance['number'] = min(max((int)$number, 1), 20);
      $instance['date_limit'] = min(max((int)$date_limit, 0), 999);
      $instance['post_type'] = post_type_exists($post_type) ? $post_type : 'post';

      $this->flush_widget_cache();

      return $instance;
    }



   /*
    * Clears widget cache.
    * Should run after a post has been rated, deleted, edited etc...
    *
    * @since 1.0
    */
    public function flush_widget_cache(){
      wp_cache_delete('widget_top_rated', 'widget');
    }



   /*
    * @since   1.0
    * @see     WP_Widget::form
    */
    public function form($instance){

      $plugin_options = PostRatings()->getOptions();
      $title = isset($instance['title']) ? esc_attr($instance['title']) : __('Top Rated', PostRatings::ID);
      $sort = isset($instance['sort']) ? esc_attr($instance['sort']) : 'bayesian_rating';
      $order = in_array($instance['order'], array('ASC', 'DESC'), true) ? $instance['order'] : 'DESC';
      $date_limit = isset($instance['date_limit']) ? (int)$instance['date_limit'] : 0;
      $number = isset($instance['number']) ? min(max((int)$instance['number'], 1), 20) : 5;
      $post_type = post_type_exists($instance['post_type']) ? $instance['post_type'] : 'post';

      ?>
      <fieldset>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', PostRatings::ID); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
      </p>

      <p>
        <label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Get most rated:', PostRatings::ID); ?></label>
        <select class="widefat" id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>">
          <?php foreach(get_post_types(array('public' => true)) as $type): ?>
          <?php $object = get_post_type_object($type); ?>
          <option <?php if(!in_array($type, $plugin_options['post_types'])): ?> disabled="disabled" <?php endif; ?> value="<?php echo $type; ?>" <?php selected($type, $post_type); ?>><?php echo $object->labels->name; ?></option>
          <?php endforeach; ?>
        </select>
      </p>

      <p>
       <label for="<?php echo $this->get_field_id('sort'); ?>"><?php _e('Sort by:', PostRatings::ID); ?></label>
       <select class="widefat" id="<?php echo $this->get_field_id('sort'); ?>" name="<?php echo $this->get_field_name('sort'); ?>">
         <option <?php selected('bayesian_rating', $sort); ?> value="bayesian_rating"><?php _e('Overall bayesian rating (score)', PostRatings::ID); ?></option>
         <option <?php selected('rating', $sort); ?> value="rating"><?php _e('Average rating', PostRatings::ID); ?></option>
         <option <?php selected('votes', $sort); ?> value="votes"><?php _e('Number of votes', PostRatings::ID); ?></option>
       </select>
      </p>

      <p>
       <label for="<?php echo $this->get_field_id('order'); ?>_desc">
         <input id="<?php echo $this->get_field_id('order'); ?>_desc" name="<?php echo $this->get_field_name('order'); ?>" type="radio" value="DESC" <?php checked('DESC', $order); ?> />
         <?php _e('Descending', PostRatings::ID); ?>
       </label>

       <label for="<?php echo $this->get_field_id('order'); ?>_asc">
         <input id="<?php echo $this->get_field_id('order'); ?>_asc" name="<?php echo $this->get_field_name('order'); ?>" type="radio" value="ASC" <?php checked('ASC', $order); ?> />
         <?php _e('Ascending', PostRatings::ID); ?>
       </label>
      </p>

      <p>
        <label for="<?php echo $this->get_field_id('date_limit'); ?>"><?php _e('Ignore posts older than:', PostRatings::ID); ?></label>
        <input id="<?php echo $this->get_field_id('date_limit'); ?>" name="<?php echo $this->get_field_name('date_limit'); ?>" type="text" value="<?php echo $date_limit; ?>" size="3" /> <?php _e('days', PostRatings::ID); ?>
        <br />
        <small><?php _e('(0 to ignore date)', PostRatings::ID); ?></small>
      </p>

      <p>
        <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Limit:', PostRatings::ID); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
      </p>
      </fieldset>
      <?php
    }

  }


}
