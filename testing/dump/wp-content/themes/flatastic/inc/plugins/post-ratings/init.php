<?php
/*
Plugin Name: Post Ratings
Version: 2.4
Plugin URI: http://digitalnature.eu/forum/plugins/post-ratings/
Description: Simple, developer-friendly, straightforward post rating plugin. Relies on post meta to store avg. rating / vote count.
Author: digitalnature
Author URI: http://digitalnature.eu/
Text Domain: post-ratings
Domain Path: /lang
*/



/*
 * Public methods you can call from outside:
 *
 *   PostRatings()->getControl()                 - gets the rate links
 *   PostRatings()->currentUserCanRate($post_id) - checks if the current user can rate that post
 *   PostRatings()->getTopRated($options)        - gets a list of top rated posts...
 *
 *
 *
 */



/*
 * PostRatings Class
 *
 * @since 1.0
 */
class PostRatings{


  const
    VERSION      = '2.4',                                                   // plugin version
    PROJECT_URI  = 'http://digitalnature.eu/forum/plugins/post-ratings/',   // url to support forums
    ID           = 'post-ratings',                                          // internally used for text domain, theme option group name etc.
    MIN_VOTES    = 1,                                                       // minimum vote count (MV)
    BR1          = '(v / (v + MV)) * r + (MV / (v + MV)) * R',              // bayesian rating formula: the IMDB version
    BR2          = '((AV * R) + (v * r)) / (AV + v)';                       // bayesian rating formula: thebroth.com version

  protected static $instance;

  protected
    $options     = null,

    // stores rated post IDs for the current session;
    // we're using this for to get the rated state in our ajax calls
    $rated_posts = array(),

    // default option values
    $defaults    = array(
                     'version'     => self::VERSION,
                     'anonymous_vote'   => true,
                     'max_rating'       => 5,
                     'bayesian_formula' => self::BR1,
                     'user_formula'     => '',
                     'custom_filter'    => '',
                     'before_post'      => false,
                     'after_post'       => false,
                     'post_types'       => array('post'),
                     'visibility'       => array('home', 'singular', 'search', 'archive'),  // same as WP conditional "tags", but with "is_" omitted

                     // internal, global stats
                     'avg_rating'       => 0,
                     'num_votes'        => 0,
                     'num_rated_posts'  => 0,
                   );


 /*
  * This will instantiate the class if needed, and return the only class instance if not...
  *
  * @since 1.0
  */
  public static function app(){

    // first run?
    if(!(self::$instance instanceof self)){

      self::$instance = new self();

      // localize
      load_plugin_textdomain(self::ID, false, MAD_INC_PLUGINS_URI . 'post-ratings/lang');

      if(is_admin()){
        add_action('admin_menu', array(self::$instance, 'CreateMenu'));
        add_action('admin_init', array(self::$instance, 'RegisterSettings'));

      }else{
        add_action('wp', array(self::$instance, 'Run'));
      }

      // register shortcode
      add_shortcode('rate', array(self::$instance, 'Shortcode'));

      // register widget
      //add_action('widgets_init', array(self::$instance, 'Widget'));

      // empty our cache when a new post is written or when a post gets deleted
      add_action('save_post',    array(self::$instance, 'clearQueryCache'));
      add_action('deleted_post', array(self::$instance, 'clearQueryCache'));

      // run on plugin uninstall; not sure when does this run?!
      register_uninstall_hook(__FILE__, array(__CLASS__, 'Uninstall'));
    }

    return self::$instance;
  }





 /*
  * A single instance only
  *
  * @since 1.0
  */
  final protected function __construct(){

    // initialize early, to avoid endless loop within update_option() + settings validation callback.
    // stupid settings API...
    $this->getOptions();
  }



 /*
  * No cloning
  *
  * @since 1.0
  */
  final protected function __clone(){}




  /*
   * Returns one or all plugin options.
   *
   * @since   1.0
   * @param   string $key   Option to get; if not given all options are returned
   * @return  mixed         Option(s)
   */
  public function getOptions($key = false){

      // first call, initialize the options
    if(!isset($this->options)){

      $options = get_option(self::ID);

      // options exist
      if($options !== false){

        if(!isset($options['version']))
          $options['version'] = '1.0';

        $new_version = version_compare($options['version'], self::VERSION, '!=');
        $desync = array_diff_key($this->defaults, $options) !== array_diff_key($options, $this->defaults);

        // update options if version changed, or we have missing/extra (out of sync) option entries
        if($new_version || $desync){

          $new_options = array();

          // check for new options and set defaults if necessary
          foreach($this->defaults as $option => $value)
            $new_options[$option] = isset($options[$option]) ? $options[$option] : $value;

          // update version info
          $new_options['version'] = self::VERSION;

          update_option(self::ID, $new_options);
          $this->options = $new_options;

        // no update was required
        }else{
          $this->options = $options;
        }


      // new install (plugin was just activated)
      }else{
        update_option(self::ID, $this->defaults);
        $this->options = $this->defaults;
      }
    }

    return $key ? $this->options[$key] : $this->options;
  }



  /*
   * Loads a template file from the theme or child theme directory.
   *
   * @since   1.0
   * @param   string $_name   Template name, without the '.php' suffix
   * @param   array $_vars    Variables to expose in the template. Note that unlike WP, we're not exposing all the global variable mess inside it...
   */
  final public function loadTemplate($_name, $_vars = array()) {

    // you cannot let locate_template to load your template
    // because WP devs made sure you can't pass
    // variables to your template :(
    $_located = locate_template("{$_name}.php", false, false);

    // use the default one if the (child) theme doesn't have it
    if(!$_located)
      $_located = MAD_INC_PLUGINS_PATH .'post-ratings/templates/' . $_name . '.php';

    unset($_name);

    // create variables
    if($_vars)
      extract($_vars);

    ob_start();

    // load it
    require $_located;

    return ob_get_clean();
  }



 /*
  * Hook our plugin options menu / page
  *
  * @since 1.0
  */
  public function CreateMenu(){
    add_theme_page(__('Post Ratings', self::ID), __('Post Ratings', self::ID), 'manage_options', self::ID, array($this, 'SettingsPage'), 62);
  }



 /*
  * Register our setting with the new useless Settings API bloat...
  *
  * @since 1.0
  */
  public function RegisterSettings(){
    register_setting(self::ID, self::ID, array($this, 'ValidateSettings'));
    add_filter('plugin_action_links', array($this, 'PluginSettingsLink'), 10, 2);
  }



 /*
  * Settings link in the plugin list
  *
  * @since    1.0
  * @param    string $file
  * @param    array $links
  * @return   array
  */
  public function PluginSettingsLink($links, $file){

    if (MAD_INC_PLUGINS_URI . 'post-ratings/' === $file) {
      $settings_link = '<a href="'.add_query_arg(array('page' => self::ID), admin_url('options-general.php')).'">'.__('Settings', self::ID).'</a>';
      array_unshift($links, $settings_link);
    }

    return $links;
  }



 /*
  * Validate user input for our settings
  *
  * @since    1.0
  * @param    array $input
  * @return   array
  */
  public function ValidateSettings($input){

    extract($input);

    $options = array(
      'anonymous_vote'   => isset($anonymous_vote) ? true : false,
      'max_rating'       => min(max((int)$max_rating, 1), 10),
      'bayesian_formula' => wp_filter_nohtml_kses($bayesian_formula),

      // only allow super admins to change this, because it's a little sensitive (part of this string is used inside the top rated db query)
      'user_formula'     => current_user_can('edit_plugins') ? wp_filter_nohtml_kses($user_formula) : $this->getOptions('user_formula'),

      'before_post'      => isset($before_post) ? true : false,
      'after_post'       => isset($after_post) ? true : false,
      'custom_filter'    => wp_filter_nohtml_kses($custom_filter),
      'post_types'       => isset($post_types) ? array_filter($post_types, 'post_type_exists') : array(),
      'visibility'       => isset($visibility) ? array_map('wp_filter_nohtml_kses', $visibility) : array(),

      // internal, global stats
      'avg_rating'       => $this->getOptions('avg_rating'),
      'num_votes'        => $this->getOptions('num_votes'),
      'num_rated_posts'  => $this->getOptions('num_rated_posts'),

      'version'          => self::VERSION,
    );

    if(isset($remove_ratings) || ($options['max_rating'] !== $this->getOptions('max_rating'))){
      $options['avg_rating'] = $options['num_votes'] = $options['num_rated_posts'] = 0;
      $this->DeleteRatingRecords();
    }

    return $options;
  }



 /*
  * The options page (form)
  *
  * @since 1.0
  */
  public function SettingsPage(){

    $options = $this->getOptions();
    extract($options);

    $generic_pages = array(
      'home'     => __('Home', self::ID),
      'archive'  => __('Archives', self::ID),
      'singular' => __('Single pages', self::ID),
      'search'   => __('Search results', self::ID),
    );

    ?>
    <div class="wrap metabox-holder">
      <h2><?php _e('Post Ratings', self::ID); ?></h2>

      <form method="post" action="options.php" style="position:relative;">

        <div class="postbox" style="position:absolute;right:0;top:0;">
          <h3><span><?php _e('Global stats', self::ID); ?></span></h3>
          <div class="inside">
            <p><?php printf(__('%1$s votes (on %2$s posts)', self::ID), sprintf('<strong>%d</strong>', $num_votes), sprintf('<strong>%d</strong>', $num_rated_posts)); ?></p>
            <p><?php printf(__('Average vote count per post: %s', self::ID), sprintf('<strong>%.2F</strong>', @($options['num_votes'] / $options['num_rated_posts']))); ?></p>
            <p><?php printf(__('Average rating per post: %s', self::ID), sprintf('<strong>%.2F</strong>', $avg_rating)); ?></p>
          </div>
        </div>

        <?php settings_fields(self::ID); ?>

        <table class="form-table">

          <tr valign="top">
            <th scope="row"><?php _e('Access level', self::ID); ?></th>
            <td>
              <label for="anonymous_vote">
                <input type="checkbox" id="anonymous_vote" name="<?php echo self::ID; ?>[anonymous_vote]" value="cookie" <?php checked($anonymous_vote); ?> />
                <?php _e('Allow unregistered users to vote', self::ID); ?>
             </label>
            </td>
          </tr>

          <tr valign="top">
            <th scope="row"><?php _e('Maximum rating', self::ID); ?></th>
            <td>
              <input type="text" size="3" name="<?php echo self::ID; ?>[max_rating]" value="<?php echo $max_rating; ?>" />
              <p><span class="description"><?php _e('Changing this option will reset existing post rating records', self::ID); ?></span></p>
            </td>
          </tr>

          <tr valign="top">
            <th scope="row">
              <?php _e('Bayesian rating (score) formula', self::ID); ?>
            </th>
            <td>

              <fieldset>
               <p>
               <label for="bayesian_formula_1">
                 <input id="bayesian_formula_1" name="<?php echo self::ID; ?>[bayesian_formula]" type="radio" value="<?php echo self::BR1; ?>" <?php checked($bayesian_formula, self::BR1); ?> />

                  <code style="font-size: 14px;">(<em>v</em> / (<em>v</em> + <strong>MV</strong>)) * <em>r</em> + (<strong>MV</strong> / (<em>v</em> + <strong>MV</strong>)) * <strong>R</strong></code> (<?php printf(__('from %s', self::ID), '<a href="http://en.wikipedia.org/wiki/Internet_Movie_Database#User_ratings_of_films" target="_blank">IMDB</a>'); ?>)
               </label>
               </p>

               <p>
               <label for="bayesian_formula_2">
                 <input id="bayesian_formula_2" name="<?php echo self::ID; ?>[bayesian_formula]" type="radio" value="<?php echo self::BR2; ?>" <?php checked($bayesian_formula, self::BR2); ?> />
                 <code style="font-size: 14px;">((<strong>AV</strong> * <strong>R</strong>) + (<em>v</em> * <em>r</em>)) / (<strong>AV</strong> + <em>v</em>)</code> (<?php printf(__('from %s', self::ID), '<a href="https://gist.github.com/44522/" target="_blank">thebroth</a>'); ?>)
               </label>
               </p>

               <p>
               <label for="user_formula">
                 <input id="user_formula" name="<?php echo self::ID; ?>[bayesian_formula]" type="radio" value="0" <?php checked($bayesian_formula, 0); ?> />
                 <?php _e('I have my own formula:', self::ID); ?>
                 <input <?php if(!current_user_can('edit_plugins')): ?>disabled="disabled"<?php endif; ?> type=text name="<?php echo self::ID; ?>[user_formula]" size="46" class="code" value="<?php echo $user_formula; ?>" />
               </label> <a href="#" onclick="jQuery('#legend').toggle();">(<?php _e('Legend', self::ID); ?>)</a>
               </p>

               <div id="legend" style="display:none;">
                <p>
                 <code style="font-size: 14px;"><strong>AV</strong></code> = <?php _e('Global average number of votes per post', self::ID); ?>
                 <br />
                 <code style="font-size: 14px;"><strong>&nbsp;V</strong></code> = <?php _e('Global number of votes (from all posts)', self::ID); ?>
                 <br />
                 <code style="font-size: 14px;"><em>&nbsp;v</em></code> = <?php _e('Number of votes from the current post', self::ID); ?>
                 <br />
                 <code style="font-size: 14px;"><strong>&nbsp;R</strong></code> = <?php printf(__('Global average rating per post (from 1 to %d)', self::ID), $max_rating); ?>
                 <br />
                 <code style="font-size: 14px;"><em>&nbsp;r</em></code> = <?php printf(__('Average rating of the current post (from 1 to %d)', self::ID), $max_rating); ?>
                 <br />
                 <code style="font-size: 14px;"><strong>MV</strong></code> = <?php printf(__('Minimum vote count per post to consider (%d by default)', self::ID), self::MIN_VOTES); ?>
                 <br />
                 <code style="font-size: 14px;"><strong>MR</strong></code> = <?php printf(__('Maximum rating, see option above (currently %d)', self::ID), $max_rating); ?>
               </p>
              </div>

              </fieldset>
            </td>
          </tr>

          <tr valign="top">
            <th scope="row"><?php _e('Allow ratings on', self::ID); ?></th>
            <td>
              <fieldset>
                 <?php foreach(get_post_types(array('public' => true)) as $type): ?>
                 <?php $object = get_post_type_object($type); ?>
                 <label for="post_type_<?php echo $type; ?>">
                   <input type="checkbox" value="<?php echo $type; ?>" id="post_type_<?php echo $type; ?>" name="<?php echo self::ID; ?>[post_types][]" <?php checked(in_array($type, $post_types)); ?> />
                   <?php echo $object->labels->name; ?>
                 </label>
                 <br />
                 <?php endforeach; ?>
              </fieldset>
            </td>
          </tr>

          <tr valign="top">
            <th scope="row"><?php _e('Locations of the rate links', self::ID); ?></th>
            <td>

              <fieldset>
               <label for="location_before_post">
                 <input id="location_before_post" name="<?php echo self::ID; ?>[before_post]" type="checkbox" <?php checked($before_post); ?> />
                 <?php _e('Before post content', self::ID); ?>
               </label>
               <br />
               <label for="location_after_post">
                 <input id="location_after_post" name="<?php echo self::ID; ?>[after_post]" type="checkbox" <?php checked($after_post); ?> />
                 <?php _e('After post content ', self::ID); ?>
               </label>
               <br />
               <br />
               <label for="custom_filter">
                  <?php _e('I have my own action hook:', self::ID); ?>
                  <input id="custom_filter" class="code" type="text" value="<?php echo $custom_filter; ?>" name="<?php echo self::ID; ?>[custom_filter]" size="40" />
               </label>
              </fieldset>

              <p>
                <span class="description"><?php printf(__('You can also add it manually anywhere by using the %s shortcode', self::ID), '<code>[rate]</code>'); ?></span>
              </p>

            </td>
          </tr>

          <tr valign="top">
            <th scope="row"><?php _e('Page visibility', self::ID); ?></th>
            <td>
              <fieldset>
                 <?php foreach($generic_pages as $page => $label): ?>
                 <label for="visibility_<?php echo $page; ?>">
                   <input type="checkbox" value="<?php echo $page; ?>" id="visibility_<?php echo $page; ?>" name="<?php echo self::ID; ?>[visibility][]" <?php checked(in_array($page, $visibility)); ?> />
                   <?php echo $label; ?>
                 </label>
                 <br />
                 <?php endforeach; ?>
              </fieldset>
            </td>
          </tr>

          <tr><td colspan="2">&nbsp;</td></tr>

            <tr valign="top">

            <th scope="row" colspan="2">

              <input type="submit" class="button-primary" value="<?php _e('Save Changes', self::ID); ?>" />
              &nbsp;
              <label for="remove_all">
                <input id="remove_all" type="checkbox" value="1" name="<?php echo self::ID; ?>[remove_ratings]" />
                <?php _e('Delete rating records from all posts', self::ID); ?>
              </label>

            </th>

          </tr>

        </table>

      </form>
      <div style="background:#eee;padding: 5px 10px;margin: 10px 5px;">
        <?php printf(__('Found a bug, having a feature request or just looking for help on using this plugin? Then head on to the %s.', self::ID), '<a href="'.self::PROJECT_URI.'">'.__('Post Ratings support forums', self::ID).'</a>'); ?>
      </div>
    </div>
    <?php
  }



 /*
  * Javascript and CSS used by the plugin
  *
  * @since 1.0
  */
  public function assets(){

    // js
    wp_enqueue_script('jquery');
    wp_enqueue_script(self::ID, MAD_INC_PLUGINS_URI . 'post-ratings/post-ratings.js', array('jquery'), self::VERSION, true);

    // note that Atom-based themes alread have this variable "localized"
    if(!class_exists('Atom') || (class_exists('Atom') && (!defined('Atom::VERSION'))))
      wp_localize_script(self::ID, 'post_ratings', array('blog_url' => home_url('/')));

    // allow themes to override css
    $style = MAD_INC_PLUGINS_URI . 'post-ratings/post-ratings.css';

    wp_enqueue_style(self::ID, $style);
  }



 /*
  * Remove plugin options and rating stats on uninstall
  *
  * @since 1.0
  */
  public static function Uninstall(){
    PostRatings()->DeleteRatingRecords();
    delete_option(self::ID);
  }



 /*
  * Get user rating for a post
  *
  * @since    1.0
  * @param    int $post_id     Post ID
  * @return   array            Rating, vote count and bayesian rating
  */
  public function getRating($post_id){

    $options = $this->getOptions();
    extract($options);

    $rating = (float)get_post_meta($post_id, 'rating', true);
    $votes = (int)get_post_meta($post_id, 'votes', true);

    $bayesian_rating = 0;

    if($votes != 0){
      $avg_num_votes = ($num_rated_posts != 0) ? ($num_votes / $num_rated_posts) : 0;

      $identifiers = array(
        'AV' => $avg_num_votes,
        'MV' => self::MIN_VOTES,
        'MR' => $max_rating,
        'V'  => $num_votes,
        'v'  => $votes,
        'R'  => $avg_rating,
        'r'  => $rating,
      );

      if(!$bayesian_formula)
        $bayesian_formula = $user_formula;

      if(!$bayesian_formula)
        $bayesian_formula = 'r';

      $bayesian_formula = strtr($bayesian_formula, $identifiers);

      // only super admins can set their own formula
      //$bayesian_rating = (float)@eval("return ({$bayesian_formula});");
	  $bayesian_rating = (float) ($bayesian_formula);
	  
      $bayesian_rating = 100 * ($bayesian_rating / $max_rating);
    }

    return compact('rating', 'votes', 'bayesian_rating');
  }



 /*
  * Adjust user meta key name, or cookie key name for multisite blogs (except primary blog)
  *
  * @since    2.0
  * @param    string
  * @return   string
  */
  private function getRecordsKey($key){
    if(is_multisite() && !is_main_site())
      $key .= '_'.get_current_blog_id();

    return $key;
  }



 /*
  * Attempt to get the visitor's IP address
  *
  * @since    2.3
  * @return   string
  */
  private function getIP(){

    if(isset($_SERVER['HTTP_CLIENT_IP']))
      return $_SERVER['HTTP_CLIENT_IP'];

    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
      return $_SERVER['HTTP_X_FORWARDED_FOR'];

    if(isset($_SERVER['HTTP_X_FORWARDED']))
      return $_SERVER['HTTP_X_FORWARDED'];

    if(isset($_SERVER['HTTP_FORWARDED_FOR']))
      return $_SERVER['HTTP_FORWARDED_FOR'];

    if(isset($_SERVER['HTTP_FORWARDED']))
      return $_SERVER['HTTP_FORWARDED'];

    return $_SERVER['REMOTE_ADDR'];
  }



 /*
  * Process rating, or set up plugin hooks if this is not a rate request
  *
  * @since 1.0
  */
  public function Run(){

    $options = $this->getOptions();
    extract($options);

    if(!isset($_GET['rate'])){
      if($custom_filter)
        add_filter($custom_filter, array($this, 'ControlBlockHook'));

      if($before_post || $after_post){
        // post content
        add_filter('the_content', array($this, 'ControlBlockHook'), 20);

        // bbpress
        add_filter('bbp_get_topic_content', array($this, 'ControlBlockHook'));
        add_filter('bbp_get_reply_content', array($this, 'ControlBlockHook'));
      }

      add_action('wp_enqueue_scripts', array($this, 'assets'));

    // this is our $.ajax request
    }else{

      defined('DOING_AJAX') or define('DOING_AJAX', true);

      $post_id  = (int)$_GET['post_id'];
      $voted    =  min(max((int)$_GET['rate'], 1), $max_rating);
      $error    = '';
      $post     = &get_post($post_id);
      $rating   = 0;
      $votes    = 0;

      if(!$post){
        $error = __("Invalid vote! Cheatin' uh?", self::ID);

      }else{

        // get current post rating and vote count
        extract($this->getRating($post->ID));

        // vote seems valid, register it
        if($this->currentUserCanRate($post_id)){

          // increase global post rate count if this is the first vote
          if($votes < 1)
            $options['num_rated_posts']++;

          // global vote count
          $options['num_votes']++;

          // update post rating and vote count
          $votes++;
          $rating = (($rating * ($votes - 1)) + $voted) / $votes;

          update_post_meta($post->ID, 'rating', $rating);
          update_post_meta($post->ID, 'votes', $votes);

          // update global stats
          $options['avg_rating'] = ($options['num_votes'] > 0) ? ((($options['avg_rating'] * ($options['num_votes'] - 1))  + $voted) / $options['num_votes']) : 0;
          update_option(self::ID, $options);

          $ip_cache = get_transient('post_ratings_ip_cache');

          if(!$ip_cache)
            $ip_cache = array();

          $posts_rated = isset($_COOKIE[$this->getRecordsKey('posts_rated')]) ? explode('-', $_COOKIE[$this->getRecordsKey('posts_rated')]) : array();
          $posts_rated = array_map('intval', array_filter($posts_rated));

          // add user's IP to the cache
          $ip_cache[$post_id][] = $this->getIP();

          // keep it light, only 10 records per post and maximum 10 post records (=> max. 100 ip entries)
          // also, the data gets deleted after 2 weeks if there's no activity during this time...

          if(count($ip_cache[$post_id]) > 10)
            array_shift($ip_cache[$post_id]);

          if(count($ip_cache) > 10)
            array_shift($ip_cache);

          set_transient('post_ratings_ip_cache', $ip_cache, 60 * 60 * 24 * 14);

          // update user meta
          if(is_user_logged_in()){
            $user = wp_get_current_user();

            $current_user_ratings = get_user_meta($user->ID, $this->getRecordsKey('posts_rated'), true);

            if(!$current_user_ratings)
              $current_user_ratings = array();

            $posts_rated = array_unique(array_merge($posts_rated, array_filter($current_user_ratings)));

            update_user_meta($user->ID, $this->getRecordsKey('posts_rated'), $posts_rated);
          }

          // update cookie
          $posts_rated = array_slice($posts_rated, -20); // keep it under 20 entries
          $posts_rated[] = $post_id;
          setcookie($this->getRecordsKey('posts_rated'), implode('-', $posts_rated),  time() + 60 * 60 * 24 * 90, '/'); // expires in 90 days

          $this->rated_posts[] = $post_id;

          do_action('rated_post', $post_id);
          $this->clearQueryCache();

        }else{
          $error = __('You cannot rate this post!', self::ID);
        }

      }

      // send updated info
      echo json_encode(array(
        'error'      => $error,
        'rating'     => sprintf('%.2F', $rating),
        'max_rating' => $max_rating,
        'votes'      => $votes,
        'html'       => $this->getControl($post_id, true),
      ));

      exit;
    }

  }



 /*
  * Delete all ratings-related meta data from the database
  *
  * @since 1.0
  */
  public function DeleteRatingRecords(){

    // clear cache, just in case we have a persistent cache plugin active
    wp_cache_flush();

    delete_transient('post_ratings_ip_cache');

    // remove all our meta entries
    delete_metadata('post', 0, 'rating', '', $delete_all = true);
    delete_metadata('post', 0, 'votes', '', $delete_all = true);
    delete_metadata('user', 0, $this->getRecordsKey('posts_rated'), '', $delete_all = true);

    // delete the current user's cookie too; this is probably useless because it only handles the current user;
    // we should store a unique ID on both the server and client computer
    // and if this ID doesn't match with the one on the user's computer then expire his cookie
    if(isset($_COOKIE[$this->getRecordsKey('posts_rated')]))
      setcookie($this->getRecordsKey('posts_rated'), null, -1, '/');
  }



 /*
  * Hook for the content
  *
  * @since     1.8
  * @param     string $content
  * @return    string
  */
  public function ControlBlockHook($content = ''){
    global $post, $wp_current_filter;

    $control = $this->getControl();

    if($control){

      $options = $this->getOptions();

      extract($options);

      // no post ID?
      // this is most likely the user's action tag, fired in the wrong place...
      if(empty($post->ID)){
        printf(__("Your '%s' action is must run in a post's context!", self::ID), $custom_filter);
        return $content;
      }

      // check if this is the right post type
      if(!in_array(get_post_type($post->ID), $post_types))
        return $content;

      // we don't want to insert our html in excerpts...
      // see here why: http://digitalnature.eu/blog/2011/09/12/how-to-correctly-hook-your-filter-to-the-post-content
      if(array_intersect(array('get_the_excerpt', 'the_excerpt'), $wp_current_filter))
        return $content;

      $continue = false;

      // this is the user's custom action, so directly output the HTML
      if(in_array($custom_filter, $wp_current_filter)){
        echo $control;

      // the_content
      }elseif(array_intersect(array('the_content', 'bbp_get_reply_content'), $wp_current_filter)){

        // we don't want to mess with custom loops
        if(in_the_loop()){
          if($before_post)
            $content = $control.$content;

          if($after_post)
            $content = $content.$control;
        }

      }
    }

    return $content;
  }



 /*
  * The rate links
  *
  * @since     1.0
  * @param     int $post_id
  * @param     bool $ignore_visibility_setting
  * @return    string
  */
  public function getControl($post_id = '', $ignore_visibility_setting = false){
    global $post;

    $control = array();
    $options = $this->getOptions();
    $post_id = $post_id ? $post_id : $post->ID;

    extract($options);

    // check if this is the right post type
    if(!in_array(get_post_type($post_id), $post_types))
      return false;

    if(empty($post_id))
      throw new Exception('Need a post ID...');

    $continue = false;

    if(!$ignore_visibility_setting){

      // page visibility check
      foreach($visibility as $page)
        if(call_user_func("is_{$page}"))
          $continue = true;

      // cpt archive check
      if(in_array('archive', $visibility) && is_post_type_archive($post_types))
        $continue = true;

      $continue = apply_filters('post_ratings_visibility', $continue);

    }

    if($continue || $ignore_visibility_setting){

      // get current post rating
      extract($this->getRating($post_id));

      $post = get_post($post_id);
      setup_postdata($post);

      $loaded = $this->loadTemplate('post-ratings-control', compact('rating', 'votes', 'bayesian_rating', 'max_rating'));

      wp_reset_postdata();

      return $loaded;
    }

    return false;
  }



 /*
  * Checks if the current user can rate a post.
  *
  * @since    1.0
  * @param    int $post_id     Optional, post ID to check (if not given, the global $post is used)
  * @return   bool
  */
  public function currentUserCanRate($post_id = false){

    global $post;

    $post_id = $post_id ? $post_id : $post->ID;

    $can_rate = false;

    if(in_array($post_id, $this->rated_posts))
      return false;

    // check if ratings are enabled for this post type
    if(in_array(get_post_type($post_id), $this->getOptions('post_types')))

      // check if the user is logged in; if not, only continue if anonymouse voting is allowed
      if($this->getOptions('anonymous_vote') || is_user_logged_in()){

        // last 100 IPs
        $ip_cache = get_transient('post_ratings_ip_cache');

        // client cookie
        $posts_rated = isset($_COOKIE[$this->getRecordsKey('posts_rated')]) ? explode('-', $_COOKIE[$this->getRecordsKey('posts_rated')]) : array();

        // also get user meta rating records if user is logged in
        if(is_user_logged_in()){
          $user = wp_get_current_user();
          $posts_rated = array_merge($posts_rated, (array)get_user_meta($user->ID, $this->getRecordsKey('posts_rated'), true));
        }

        $can_rate = !((isset($ip_cache[$post_id]) && in_array($this->getIP(), $ip_cache[$post_id])) || in_array($post_id, $posts_rated));

      }

    return apply_filters('post_ratings_access_check', $can_rate, $post_id);
  }



 /*
  * Get a list of most rated posts.
  * The results are returned as an array of objects
  *
  * @since     1.0
  * @param     array $args    Arguments, see below
  * @return    array
  */
  public function getTopRated($args){
    global $wpdb;

    $args = wp_parse_args($args, array(
      'post_type'        => 'post',
      'number'           => 10,
      'offset'           => 0,
      'sortby'           => 'bayesian_rating',    // bayesian_rating, rating or votes
      'order'            => 'DESC',               // ASC or DESC (no reason to use ASC...)
      'date_limit'       => 0,                    // date limit in days
      'where'            => '',
      'bayesian_formula' => $this->getOptions('bayesian_formula'),
    ));

    $options = $this->getOptions();
    extract($options);
    extract($args);

    // averge votes per post
    $avg_num_votes = ($num_rated_posts != 0) ? ($num_votes / $num_rated_posts) : 0;

    $where = $date_limit ? "AND post_date > '".date('Y-m-d', strtotime(sprintf('-%d days', $date_limit)))."'" : $where;

    if(empty($bayesian_formula))
      $bayesian_formula = $user_formula;

    if(!$bayesian_formula)
      $bayesian_formula = 'r';

    $identifiers = array(
      'AV' => $avg_num_votes,
      'MV' => self::MIN_VOTES,
      'MR' => $max_rating,
      'V'  => $num_votes,
      'v'  => 'votes',
      'R'  => $avg_rating,
      'r'  => 'rating',
    );

    $bayesian_formula = strtr($bayesian_formula, $identifiers);

    // many thanks for this SQL query to Utku Yıldırım :)
    // http://stackoverflow.com/questions/8214902/order-database-results-by-bayesian-rating/8215068#8215068
    $query = "
      SELECT *, {$bayesian_formula} AS bayesian_rating
      FROM {$wpdb->posts}
      LEFT JOIN(
       SELECT DISTINCT post_id,
        (SELECT CAST(meta_value AS DECIMAL(10)) FROM {$wpdb->postmeta} WHERE {$wpdb->postmeta}.post_id = meta.post_id AND meta_key ='votes') AS votes,
        (SELECT CAST(meta_value AS DECIMAL(10,2)) FROM {$wpdb->postmeta} WHERE {$wpdb->postmeta}.post_id = meta.post_id AND meta_key ='rating') AS rating
        FROM {$wpdb->postmeta} meta )
       AS newmeta ON {$wpdb->posts}.ID = newmeta.post_id
      WHERE post_status = 'publish' AND post_type = '{$post_type}' {$where}
      GROUP BY ID
      ORDER BY {$sortby} {$order}
      LIMIT {$offset}, {$number}
    ";

    // check cache first
    $key = md5($query);
    $cache = wp_cache_get('get_top_rated', self::ID);

    if(isset($cache[$key])){
      $results = $cache[$key];

    // no cache, do the db query...
    }else{
      $results = $wpdb->get_results($query);
      $cache[$key] = $results;
      wp_cache_set('get_top_rated', $cache, self::ID);

    }

    return $results;
  }



 /*
  * Flushes cached queries.
  *
  * @since     1.9
  */
  public function clearQueryCache(){
    wp_cache_delete('get_top_rated');
  }



 /*
  * The [rate] shortcode
  *
  * @since     1.0
  * @params    array $atts     Can accept the post ID as argument; if not given, control() will use the $post global
  * @return    string
  */
  public function Shortcode($atts){

    $post_id = '';

    // check if a post ID was given as first argument
    if(isset($atts[0]) && is_numeric($atts[0]))
      $post_id = (int)$atts[0];

    // no, maybe it's the 2nd argument
    elseif(isset($atts[1]) && is_numeric($atts[1]))
      $post_id = (int)$atts[1];

    // check if a "force" attribute is present
    $force = array_search('force', (array)$atts) !== false;

    return $this->getControl($post_id, $force);
  }



 /*
  * Register the "Top Rated" widget
  *
  * @since 1.0
  */
  public function Widget(){
    require dirname(__FILE__).'/widget.php';
    register_widget('PostRatingsWidget');
  }
}



// a shortcut to our application
function PostRatings(){
  static $app;

  // first call to app() initializes the plugin
  if(!($app instanceof PostRatings))
    $app = PostRatings::app();

  return $app;
}


PostRatings();








// @todo
add_filter('user_has_cap', 'post_ratings_has_cap', 10, 3);
add_filter('map_meta_cap', 'post_ratings_map_cap_for_sa', 10, 4);

function post_ratings_map_cap_for_sa($caps, $req_cap, $user_id, $args){

  // $args[0] is the post ID
  if(($req_cap === 'rate') && is_multisite() && is_super_admin($user_id) && isset($args[0]) && !PostRatings()->currentUserCanRate($args[0]))
    $caps[] = 'do_not_allow';

  return $caps;

}

function post_ratings_has_cap($allcaps, $caps, $args){

  // $args[2] is the post ID
  if($args[0] !== 'rate' && !isset($args[2]) || !PostRatings()->currentUserCanRate($args[2]))
    return $allcaps;

  $allcaps['rate'] = 1;

  return $allcaps;
}