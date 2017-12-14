<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-leads
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

define( 'TVE_LEADS_TEST_DATA', false );

define( 'TVE_LEADS_VERSION', '1.95.6' );

define( 'TVE_LEADS_DB_VERSION', '1.18' );

/**
 * Required version of TCB
 */
define( 'TVE_REQUIRED_TCB_VERSION', '1.500.6' );

define( 'TVE_LEADS_DB_PREFIX', 'tve_leads_' );

/* we keep these as integers */
define( 'TVE_LEADS_UNIQUE_IMPRESSION', 1 );
define( 'TVE_LEADS_CONVERSION', 2 );
define( 'TVE_LEADS_IMPRESSION', 3 );
define( 'TVE_LEADS_ONE_CLICK_CONVERSION', 4 );

/* POST statuses */
define( 'TVE_LEADS_STATUS_PUBLISH', 'publish' );
define( 'TVE_LEADS_STATUS_ARCHIVED', 'archived' );
define( 'TVE_LEADS_STATUS_COMPLETED', 'completed' );
define( 'TVE_LEADS_STATUS_RUNNING', 'running' );

/**
 * test types
 */
define( 'TVE_LEADS_VARIATION_TEST_TYPE', 1 );
define( 'TVE_LEADS_GROUP_TEST_TYPE', 2 );
define( 'TVE_LEADS_SHORTCODE_TEST_TYPE', 3 );
define( 'TVE_LEADS_TWO_STEP_LIGHTBOX_TEST_TYPE', 4 );

/**
 * post types
 */
define( 'TVE_LEADS_POST_FORM_TYPE', 'tve_form_type' );
define( 'TVE_LEADS_POST_GROUP_TYPE', 'tve_lead_group' );
define( 'TVE_LEADS_POST_SHORTCODE_TYPE', 'tve_lead_shortcode' );
define( 'TVE_LEADS_POST_TWO_STEP_LIGHTBOX', 'tve_lead_2s_lightbox' );
define( 'TVE_LEADS_POST_ASSET_GROUP', 'tve_lead_asset_group' );
define( 'TVE_LEADS_POST_ONE_CLICK_SIGNUP', 'tve_lead_1c_signup' );

/**
 * screen types
 */
define( 'TVE_SCREEN_HOMEPAGE', 1 );
define( 'TVE_SCREEN_BLOG', 2 );
define( 'TVE_SCREEN_PAGE', 3 );
define( 'TVE_SCREEN_POST', 4 );
define( 'TVE_SCREEN_CUSTOM_POST', 5 );
define( 'TVE_SCREEN_ARCHIVE', 6 );
define( 'TVE_SCREEN_OTHER', 7 );

/**
 * Date interval options
 */
define( 'TVE_LAST_7_DAYS', 1 );
define( 'TVE_LAST_30_DAYS', 2 );
define( 'TVE_THIS_MONTH', 3 );
define( 'TVE_LAST_MONTH', 4 );
define( 'TVE_THIS_YEAR', 5 );
define( 'TVE_LAST_YEAR', 6 );
define( 'TVE_LAST_12_MONTHS', 7 );
define( 'TVE_CUSTOM_DATE_RANGE', 8 );

/**
 * test statuses
 * Values of ENUM set for Status column in db for table wp_tve_leads_split_test
 */
define( 'TVE_LEADS_TEST_STATUS_RUNNING', 'running' );
define( 'TVE_LEADS_TEST_STATUS_ARCHIVED', 'archived' );

/* Defined for variation that have the content hidden */
define( 'TVE_ALREADY_SUBSCRIBED_HIDDEN', 'already_subscribed_hidden' );

/**
 * Actions
 */
define( 'TVE_LEADS_ACTION_FORM_IMPRESSION', 'tve_leads_form_impression' ); //hook to apply some logic for the form that is displayed
define( 'TVE_LEADS_ACTION_FORM_CONVERSION', 'tve_leads_form_conversion' ); //allow hooking into a successful conversion

/* called when a test winner is chosen */
define( 'TVE_LEADS_ACTION_SET_TEST_ITEM_WINNER', 'tve_leads_action_set_test_item_winner' );

/**
 * Filters
 */
define( 'TVE_LEADS_FILTER_DISPLAY_GROUP_FORM', 'tve_leads_filter_display_group_form' ); //filter for displaying form