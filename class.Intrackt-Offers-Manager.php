<?php
namespace Intrackt\Offers;

/*
 * load the source for any required classes or files
 */
require_once( INTRACKT_OFFERS_PLUGIN_DIR . 'class.Intrackt-Offers-PageMain.php' );
require_once( INTRACKT_OFFERS_PLUGIN_DIR . 'class.Intrackt-Offers-PageLog.php' );
require_once( INTRACKT_OFFERS_PLUGIN_DIR . 'class.Intrackt-Offers-Actions.php' );
require_once( INTRACKT_OFFERS_PLUGIN_DIR . 'class.Intrackt-Offers-Common.php' );


/*
 * The Manager class controls the Offers
 */
class Manager {

    /*
     * Have we instantiated the class-- this is a singleton and does not produce children
     */
	private static $initiated = false;

    /**
     * Init Manager class
     */
    public static function init() {
        if ( ! self::$initiated ) {

            self::$initiated = true;

            $role=\get_role('administrator');
            $role->add_cap('intrackt_offers');
            $role->add_cap('intrackt_common');

        }

    /*
     * setup options
     */
    PageMain::optionsDefine();

    }

    /*
     * add left column links on the plugin page for this plugin
     */
    public static function actionLinksLeft($links) {
        
        $links['settings']="<a href='/wp-admin/admin.php?page=intracktoffers-main'>Settings</a>";
        $links['license']="<a href='https://intrackt.com/plugins-offers/#license' target='_blank'>License</a>";
        
        //PageLog::updateTestObjectLog('Left: $links',$links);
        
        return $links;
    
    }

    /*
     * add right column links on the plugin page for this plugin
     */
    public static function actionLinksRight($links,$file) {
        
        /*
         * skip if not me
         */
        //PageLog::updateTestLog("Right: contect='{$context}'");
        if (strpos($file,'Intrackt-Offers')===false) return $links;
        
        foreach($links as $key=>$link) $links[$key]=str_replace('<a',"<a target='_blank'",$link);
        
        $links[]="<a target='_blank' href='https://intrackt.com/plugins-offers/'>Details</a>";
        $links[]="<a target='_blank' href='https://intrackt.com/plugins-offers/#faq'>FAQ</a>";
        $links[]="<a target='_blank' href='https://intrackt.com/plugins-offers/#support'>Support</a>";
            
        return $links;
    
    }

    /*
     * activate the plugin
     */
    public static function activate() {
    
        /*
         * Fail if the WP version is too old, else acticate a timed event to
         * permit showing the main Offers page ONCE at activation
         */
        if ( version_compare( $GLOBALS['wp_version'], INTRACKT_OFFERS_MINIMUM_WP_VERSION, '<' ) ) {
            self::alertLog("Manager: WP too old to activate");
            return;
            }

        /*
         * set role caps
         */
        $role=\get_role('administrator');
        $role->add_cap('intrackt_offers');
        $role->add_cap('intrackt_common');
                
        /*
         * Also clear the log
         */
        PageLog::resetLog();
        
        /*
         * Go to Weldome page after startup
         */
        set_transient( '_intrackt_offers_menu_create', true, 30 );
        
	}

    /*
     * Let the main page to also act as a welcome page when the Offers is activated
     */
    public static function welcome() {
        
        /*
         * Bail if no activation redirect
         */
        if ( ! get_transient( '_intrackt_offers_menu_create' ) ) {
            return;
        }

        /*
         * Delete the redirect transient
         */
        delete_transient( '_intrackt_offers_menu_create' );

        /*
         * Bail if activating from network, or bulk
         */
        if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
            return;
        }

        /*
         * perform the redirect
         */
        wp_safe_redirect( add_query_arg( array( 'page' => 'intracktoffers-main' ), admin_url( 'admin.php' ) ) );

    }
    
    /*
     * create the main menu and all submenus
     */
    public static function menuCreate() {
        
        /*
         * do this menu item only once
         */
        global $intractCommonMenu;
        if ($intractCommonMenu!=1) {
            $intractCommonMenu=1;
        
            add_menu_page(
                'Intrackt',
                'Intrackt',
                'intrackt_common',
                'intrackt-common',
                array( '\Intrackt\Offers\Common','displayPage')
            );
        }
        
        add_submenu_page(
            'intrackt-common',
            'Intrackt Offers',
            'Offers',
            'intrackt_offers',
            'intracktoffers-main',
            array( '\Intrackt\Offers\PageMain','displayPage')
        );
        add_submenu_page(
            'intracktoffers-main',
            'Intrackt Offers Log',
            '--Log',
            'intrackt_offers',
            'intracktoffers-log',
            array( '\Intrackt\Offers\PageLog','displayPage')
        );
        
    }

    /*
	 * deactivate Offers plugin
	 */
	public static function deactivate( ) {
        
        /*
        * remove actions to control the product page
        */
        remove_action('ywcnp_before_suggest_price_single', array( '\Intrackt\Offers\Actions', 'productPageAboveNYP' ));
        remove_action('ywcnp_after_suggest_price_single', array( '\Intrackt\Offers\Actions', 'productPageBelowNYP' ));
        remove_action('woocommerce_after_add_to_cart_button', array( '\Intrackt\Offers\Actions', 'offersLink' ));

        /*
         * Remove the menu when it is deactivated
         */
        add_action( 'admin_head', '\Intrackt\Offers\menuDelete' );
        
	}
   
    /*
     * Deactivate the Offers menu
     */
    public static function menuDelete() {
        remove_submenu_page( 'index.php', 'offers-main' );
    }
    
    /*
     * Bail out with error message
     */
    private static function alertLog( $message) {
        trigger_error($message,E_USER_ERROR);
    }
}
