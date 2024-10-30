<?php
/**
 * @package Intrackt-Offers
 */
/*
Plugin Name: Intrackt Offers
Plugin URI: https://intrackt.com/plugins-offers/
Description: Add Enhanced features to Offers for WooCommerce (by AngellEye)
Version: 1.1.2
Author: Intrackt
Author URI: https://Intrackt.com
License: GPLv3 or later
Text Domain: Intrackt
*/

/*
Copyright 2020 Intrackt
https://intrackt.com/plugins-offers/#license
*/

/*
 * Define useful constants
 */
define( 'INTRACKT_PLUGIN_NAME_OFFERS', 'Offers' );
define( 'INTRACKT_OFFERS_VERSION', '1.1.1' );
define( 'INTRACKT_OFFERS_MINIMUM_WP_VERSION', '4.8.1' );
define( 'INTRACKT_OFFERS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'INTRACKT_OFFERS_TESTMODE', false );

/*
 * common for all plugins
 */
{
    /*
     * Common test to bail out if not eecuted from with WP
     */
    if ( !function_exists( 'add_action' ) ) {
            echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
            exit;
    }

    /*
     * load the source for any required classes or files
     */
    require_once( INTRACKT_OFFERS_PLUGIN_DIR . 'class.Intrackt-Offers-Manager.php' );
    require_once( INTRACKT_OFFERS_PLUGIN_DIR . 'class.Intrackt-Offers-Common.php' );
    
    /*
     * add links to the plugin on the plugin page
     */
    add_filter('plugin_action_links_'.plugin_basename(__FILE__),array('Intrackt\Offers\Manager','actionLinksLeft'));
    add_filter('plugin_row_meta',array('Intrackt\Offers\Manager','actionLinksRight'),10,2);

    /*
     * activate the Manager singleton
     */
    add_action( 'init', array( 'Intrackt\Offers\Manager', 'init' ) );

    /*
     * set the location of the code that activates the Offers plugin
     */
    register_activation_hook( __FILE__, array( '\Intrackt\Offers\Manager', 'activate' ) );

    /*
     * set up the process to automatically go to the main Offers page as the activation welcome page
     */
    add_action( 'admin_init', array( '\Intrackt\Offers\Manager', 'welcome' ) );

    /*
     * Add the logic to create the menu if the Comics Offers is activated
     */
    add_action('admin_menu', array( '\Intrackt\Offers\Manager', 'menuCreate' ),9);

    /*
     * set the location of the code that deactivates the Offers plugin
     */
    register_deactivation_hook( __FILE__, array( '\Intrackt\Offers\Manager', 'deactivate' ) );
    
    /*
     * add filter to add content to the intrackt common menu page
     */
    add_filter('intract_commonpage_body', array( '\Intrackt\Offers\Common', 'displayBody' ));
}

/*
 * For this plugin
 */
{
    /*
     * load the source for any required classes or files
     */
    require_once( INTRACKT_OFFERS_PLUGIN_DIR . 'class.Intrackt-Offers-Actions.php' );

    /*
    * Add actions to control the product page for all offers plugins
    */
    add_action('woocommerce_after_add_to_cart_button', array( '\Intrackt\Offers\Actions', 'offersLink' ));
    add_action('woocommerce_after_single_product', array( '\Intrackt\Offers\Actions', 'offersEnable' ));
    
    /*
     * add action at top of all pages
     */
    add_action( 'plugins_loaded',array( '\Intrackt\Offers\Actions', 'pluginsLoaded' ));

    /*
     * Add action to add code to admin footer
     */
    add_action( 'admin_footer',array( '\Intrackt\Offers\Actions', 'adminFooter' ),PHP_INT_MAX);

    /*
     * Add actions to control Offers for WooCommerce specifically
     */
    add_filter('woocommerce_product_duplicate', array ( '\Intrackt\Offers\Actions', 'productDuplicated' ), 10, 2 );

    /*
     * Add actions to control Yith Name Your Price specifically
     */
    add_action('ywcnp_before_suggest_price_single', array( '\Intrackt\Offers\Actions', 'YithNYP_productPageAbove' ));
    add_action('ywcnp_after_suggest_price_single', array( '\Intrackt\Offers\Actions', 'YithNYP_productPageBelow' ));
    
    /*
     * add filter to replace storefront make offer heading
     */
    add_filter('aeofwc_offer_form_top_message',array( '\Intrackt\Offers\Actions','filterStorefrontMakeOfferForm'),10,3);
    
    /*
     * support for Booster Multi-Currency
     */
    {
        /*
         * Process new offer before submit
         */
        add_action('woocommerce_before_offer_submit',array('\Intrackt\Offers\Actions', 'actionBeforeOfferSubmit'),10,4);
        
        /*
         * Process new offer after submit
         */
        add_action('make_offer_after_save_form_data',array('\Intrackt\Offers\Actions', 'actionAfterOfferSubmit'),10,2);
        
        /*
         * intercept all get_post_meta
         */
        add_filter('get_post_metadata',array('\Intrackt\Offers\Actions','filterGetPostMeta'),10,4);
        
        /*
         * perform action after an update_post_meta or add_post_meta
         */
        add_action('added_post_meta',array('\Intrackt\Offers\Actions','addUpdatePostMeta'),10,4);
        add_action('update_post_meta',array('\Intrackt\Offers\Actions','addUpdatePostMeta'),10,4);
        
        /*
         * intercept cart add item or getting item from session
         */
        add_filter('woocommerce_add_cart_item',array('\Intrackt\Offers\Actions','addCartItem'),5,2);
        add_filter( 'woocommerce_get_cart_item_from_session',array('\Intrackt\Offers\Actions','getCartItemFromSession'),5,3);

        
        /*
         * intercept get product price
         */
        add_filter('woocommerce_product_get_price',array('\Intrackt\Offers\Actions','cartItemGetPrice'),PHP_INT_MAX,2);
        add_filter('woocommerce_product_variation_get_price',array('\Intrackt\Offers\Actions','cartItemGetPrice'),PHP_INT_MAX,2);
        
        /*
         * hook top and bottom of email messages
         */
        add_action('woocommerce_email_header',array('\Intrackt\Offers\Actions','emailTopAction'),10,2);
        add_action('woocommerce_email_footer',array('\Intrackt\Offers\Actions','emailBottomAction'),10,1);
        
        /*
         * hook when an offer is added in the admin
         */
        add_action('admin_action_editpost',array('\Intrackt\Offers\Actions','actionBeforeAdminEditPost'),9);
        
    }

}