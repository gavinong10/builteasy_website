<?php

add_image_size( 'admin-thumb', 80, 9999 );

/*************************************************
 * LIBRARIES
 *************************************************/
require_once AVIATORS_DIR . '/libraries/Twig/Autoloader.php';
require_once AVIATORS_DIR . '/libraries/Twig/ExtensionInterface.php';
require_once AVIATORS_DIR . '/libraries/Twig/Extension.php';

require_once AVIATORS_DIR . '/libraries/wpalchemy/MetaBox.php';
require_once AVIATORS_DIR . '/libraries/wpalchemy/MediaAccess.php';

require_once AVIATORS_DIR . '/libraries/aq_resizer.php';

/*************************************************
 * PLUGINS
 *************************************************/
require_once AVIATORS_DIR . '/core/plugins/admin/admin.php';
require_once AVIATORS_DIR . '/core/plugins/templates/templates.php';
require_once AVIATORS_DIR . '/core/plugins/views/views.php';
require_once AVIATORS_DIR . '/core/plugins/flash/flash.php';
require_once AVIATORS_DIR . '/core/plugins/settings/settings.php';
require_once AVIATORS_DIR . '/core/plugins/customizations/customizations.php';

require_once AVIATORS_DIR . '/core/helpers.php';