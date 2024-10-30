<?php
namespace Intrackt\Offers;

/*
 * load the source for any required classes or files
 */
require_once( INTRACKT_OFFERS_PLUGIN_DIR . 'class.Intrackt-Offers-PageLog.php' );
require_once(\ABSPATH.'/wp-admin/includes/template.php');

/*
 * The PageMain class defines and processes the Offers main page
 */
class PageMain {

    /*
     * Have we instantiated the class-- this is a singleton and does not produce children
     */
	private static $initiated = false;
    
    /*
     * Init class
     */
	public static function init() {
        
        //PageLog::updateTestLog("PageMain::init executed");
        
		if ( ! self::$initiated ) {
    		self::$initiated = true;

		}
        
	}
    
    /*
     * display some html in the section
     */
    public static function page01Section01Text() {

    ?>
    <h3 style='font-size: 1.15em;'>Main Settings</h3>
    <?php
    
    }

    /*
     * display some html in the section
     */
    public static function page02Section01Text() {

    ?>
    <h3 style='font-size: 1.15em;'>Booster Multi-Currency Settings</h3>
    <?php
    
    }

    /*
     * validate input values
     */
    public static function optionsValidate($input) {

        /*
         * Get the options
         */
        $options = \get_option('intrackt_offers');
        
        /*
         * Validate all the options from this form (leaving others untouched)
         * If validation failed, don't change exising value.
         */
        //$option=\trim($input['intrackt_offers_linktext']);
        //if (\preg_match('/^[-a-z0-9!\?\.,:;@#\$%^&\*_ ]{1,64}$/i', $option)) {
        //    $options['linktext'] =$option;
        //    //PageLog::updateTestLog("PageMain::optionsValidate: value stored = '".$options['linktext']."'");
        //}
        $option=\trim($input['intrackt_offers_addtocarttext']);
        if (\preg_match('/^[-a-z0-9!\?\.\'\",:;@#\$%^&\*_ ]{0,64}$/i', $option)) {
            $options['addtocarttext'] =$option;
        }
        //$option=\trim($input['intrackt_offers_offerstext']);
        //if (\preg_match('/^[-a-z0-9!\?\.,:;@#\$%^&\*_ ]{1,64}$/i', $option)) {
        //    $options['offerstext'] =$option;
        //}
        $option=\trim($input['intrackt_offers_hideaddtocart']);
        if (\preg_match('/^[01]{1,1}$/i', $option)) {
            $options['hideaddtocart'] =$option;
        }
        $option=\trim($input['intrackt_offers_setqtyto1']);
        if (\preg_match('/^[01]{1,1}$/i', $option)) {
            $options['setqtyto1'] =$option;
        }
        $option=\trim($input['intrackt_offers_adminaddnote']);
        if (\preg_match('/^[-a-z0-9!\?\.\'\"\[\]\(\),:;@#\$%^&\*_ ]{1,64}$/i', $option)) {
            $options['adminaddnote'] =sanitize_text_field($option);
        }
        $option=\trim($input['intrackt_offers_admineditnote']);
        if (\preg_match('/^[-a-z0-9!\?\.\'\"\[\]\(\),:;@#\$%^&\*_ ]{1,64}$/i', $option)) {
            $options['admineditnote'] =sanitize_text_field($option);
        }
        $option=\wp_kses($_POST['intrackt_offers_storefrontnotes'],\wp_kses_allowed_html('post'));
        $options['storefrontnotes'] =$option;
        
        $option=\trim($input['intrackt_offers_toolowmsg']);
        if (\preg_match('/^[-a-z0-9!\?\.\'\",:;@#\$%^&\*_ ]*$/i', $option)) {
            $options['toolowmsg'] =$option;
        }
        $option=\trim($input['intrackt_offers_autoassign']);
        if (\preg_match('/^[01]{1,1}$/i', $option)) {
            $options['autoassign'] =$option;
        }
        $option=\trim($input['intrackt_offers_exitonly']);
        if (\preg_match('/^[01]{1,1}$/i', $option)) {
            $options['exitonly'] =$option;
        }
        $option=\trim($input['intrackt_offers_autoaccept']);
        if (\preg_match('/^[01]{1,1}$/i', $option)) {
            $options['autoaccept'] =$option;
        }
        $option=\trim($input['intrackt_offers_acceptpercent']);
        if (\preg_match('/^[0-9]{1,3}$/i', $option)) {
            if (((int)$option > 0) && ((int)$option < 101)) {
                $options['acceptpercent'] =$option;
            }
        }
        $option=\trim($input['intrackt_offers_autodecline']);
        if (\preg_match('/^[01]{1,1}$/i', $option)) {
            $options['autodecline'] =$option;
        }
        $option=\trim($input['intrackt_offers_declinepercent']);
        if (\preg_match('/^[0-9]{1,3}$/i', $option)) {
            if (((int)$option > 0) && ((int)$option < 101)) {
                $options['declinepercent'] =$option;
            }
        }
        //$option=\trim($input['intrackt_offers_defaultcurrency']);
        //$options['defaultcurrency'] =$option;
        
        
        /*
         * update options
         */
        \update_option('intrackt_offers',$options);

    }

    /*
     * Handle display of the offers link text
     */
    public static function optionsLinkText() {
    
    /*
     * create the input widget
     */
    $options = \get_option('intrackt_offers');
    echo "<input id='intrackt_offers_linktext' name='intrackt_offers_options[intrackt_offers_linktext]' size='40' type='text' value='{$options['linktext']}' />";    

    }

    /*
     * Handle display of the offers add to cart button text
     */
    public static function optionsAddToCartText() {
        
   
    /*
     * create the input widget
     */
    $options = \get_option('intrackt_offers');
    $text=str_replace('"','&quot;',$options['addtocarttext']);
    ?>
    <input id='intrackt_offers_addtocarttext' name='intrackt_offers_options[intrackt_offers_addtocarttext]' size='40' type='text' value="<?= $text ?>" />    
    <?php

    }

    /*
     * Handle display of the offers add to cart button text
     */
    //public static function optionsOffersText() {
        
   
    /*
     * create the input widget
     */
    //$options = \get_option('intrackt_offers');
    //echo "<input id='intrackt_offers_offerstext' name='intrackt_offers_options[intrackt_offers_offerstext]' size='40' type='text' value='{$options['offerstext']}' />";    

    //}

    /*
     * Handle addition of admin note when adding offer
     */
    public static function optionsAdminAddNote() {
        
   
    /*
     * create the input widget
     */
    $options = \get_option('intrackt_offers');
    $text=str_replace('"','&quot;',$options['adminaddnote']);
    ?>
    <input id='intrackt_offers_adminaddnote' name='intrackt_offers_options[intrackt_offers_adminaddnote]' size='80' type='text' value="<?= $text ?>" />
    <?php

    }

    /*
     * Handle addition of admin note when editing offer
     */
    public static function optionsAdminEditNote() {
        
   
    /*
     * create the input widget
     */
    $options = \get_option('intrackt_offers');
    $text=str_replace('"','&quot;',$options['admineditnote']);
    ?>
    <input id='intrackt_offers_admineditnote' name='intrackt_offers_options[intrackt_offers_admineditnote]' size='80' type='text' value="<?= $text ?>" />
    <?php

    }

    /*
     * Handle display of the offers add to cart button text
     */
    public static function optionsStorefrontNotes() {
        
   
    /*
     * create the input widget
     */
    $options = \get_option('intrackt_offers');
    \wp_editor(\stripslashes($options['storefrontnotes']),'intrackt_offers_storefrontnotes',$settings = array('textarea_rows'=>'10', 'editor_class'=>'intrackt_wider_css') );

    }

    /*
     * Handle display of the offers add to cart button text
     */
    public static function optionsTooLowMsg() {
        
   
    /*
     * create the input widget
     */
    $options = \get_option('intrackt_offers');
    $text=str_replace('"','&quot;',$options['toolowmsg']);
    ?>
    <input id='intrackt_offers_toolowmsg' name='intrackt_offers_options[intrackt_offers_toolowmsg]' size='100' type='text' value="<?= $text ?>" />
    <?php

    }

    /*
     * Handle auto assign of offers to new products
     */
    public static function optionsHideAddToCart() {
    
        /*
         * create the input widget
         */
        $options = \get_option('intrackt_offers');
        echo "<select id='intrackt_offers_hideaddtocart' name='intrackt_offers_options[intrackt_offers_hideaddtocart]' style='width: 75px;'>".
                "<option value=1 ".(($options['hideaddtocart']==1)?'selected ':'').">Yes</option>".
                "<option value=0 ".(($options['hideaddtocart']==0)?'selected ':'').">No</option>".
            "</select>";

    }

    /*
     * Handle auto assign of offers to new products
     */
    public static function optionsSetQtyTo1() {
    
        /*
         * create the input widget
         */
        $options = \get_option('intrackt_offers');
        echo "<select id='intrackt_offers_setqtyto1' name='intrackt_offers_options[intrackt_offers_setqtyto1]' style='width: 75px;'>".
                "<option value=1 ".(($options['setqtyto1']==1)?'selected ':'').">Yes</option>".
                "<option value=0 ".(($options['setqtyto1']==0)?'selected ':'').">No</option>".
            "</select>";

    }

    /*
     * Handle auto assign of offers to new products
     */
    public static function optionsAutoAssign() {
    
        /*
         * create the input widget
         */
        $options = \get_option('intrackt_offers');
        echo "<select id='intrackt_offers_autoassign' name='intrackt_offers_options[intrackt_offers_autoassign]' style='width: 75px;'>".
                "<option value=1 ".(($options['autoassign']==1)?'selected ':'').">Yes</option>".
                "<option value=0 ".(($options['autoassign']==0)?'selected ':'').">No</option>".
            "</select>";

    }

    /*
     * Handle auto assign of offers to new products
     */
    public static function optionsExitOnly() {
    
        /*
         * create the input widget
         */
        $options = \get_option('intrackt_offers');
        echo "<select id='intrackt_offers_exitonly' name='intrackt_offers_options[intrackt_offers_exitonly]' style='width: 75px;'>".
                "<option value=1 ".(($options['exitonly']==1)?'selected ':'').">Yes</option>".
                "<option value=0 ".(($options['exitonly']==0)?'selected ':'').">No</option>".
            "</select>";

    }

    /*
     * Handle offers autoaccept for new products
     */
    public static function optionsAutoAccept() {
    
        /*
         * create the input widget
         */
        $options = \get_option('intrackt_offers');
        echo "<select id='intrackt_offers_autoaccept' name='intrackt_offers_options[intrackt_offers_autoaccept]' style='width: 75px;'>".
                "<option value=1 ".(($options['autoaccept']==1)?'selected ':'').">Yes</option>".
                "<option value=0 ".(($options['autoaccept']==0)?'selected ':'').">No</option>".
            "</select>";

    }

    /*
     * Handle offers accept percent for new products
     */
    public static function optionsAcceptPercent() {
    
        /*
         * create the input widget
         */
        $options = \get_option('intrackt_offers');
        echo "<input id='intrackt_offers_acceptpercent' name='intrackt_offers_options[intrackt_offers_acceptpercent]' size='5' type='text' value='{$options['acceptpercent']}' />";    

    }

    /*
     * Handle auto decline for new products
     */
    public static function optionsAutoDecline() {
    
        /*
         * create the input widget
         */
        $options = \get_option('intrackt_offers');
        echo "<select id='intrackt_offers_autodecline' name='intrackt_offers_options[intrackt_offers_autodecline]' style='width: 75px;'>".
                "<option value=1 ".(($options['autodecline']==1)?'selected ':'').">Yes</option>".
                "<option value=0 ".(($options['autodecline']==0)?'selected ':'').">No</option>".
            "</select>";

    }

    /*
     * Handle offers decline percent for new products
     */
    public static function optionsDeclinePercent() {
    
        /*
         * create the input widget
         */
        $options = \get_option('intrackt_offers');
        echo "<input id='intrackt_offers_declinepercent' name='intrackt_offers_options[intrackt_offers_declinepercent]' size='5' type='text' value='{$options['declinepercent']}' />";    

    }

    /*
     * Handle auto decline for new products
     */
    //public static function optionsDefaultCurrency() {
    
    //    global $wpdb;
        
        /*
         * create the input widget
         */
    //    $options = \get_option('intrackt_offers');
    //    echo "<select id='intrackt_offers_defaultcurrency' name='intrackt_offers_options[intrackt_offers_defaultcurrency]' style='width: 75px;'>";
    //    $currencies=$wpdb->get_col("
    //        SELECT option_value
    //        FROM {$wpdb->prefix}options
    //        WHERE option_name LIKE 'wcj_multicurrency_currency%'
    //        ");
    //    foreach ($currencies as $currency) {
    //        echo "<option value='{$currency}' ".(($options['defaultcurrency']==$currency)?'selected ':'').">{$currency}</option>";
    //    }
    //    echo "</select>";

    //}

    /*
     * Define Options
     */
    public static function optionsDefine() {
        
        //PageLog::updateTestLog("PageMain::optionsDefine: start");
        
        /*
         * get options and set this one to the default if not set
         */
        $options = \get_option('intrackt_offers');
        if (!array_key_exists('linktext',$options)) {
            $options['linktext']='Or offers';
        }
        if (!array_key_exists('addtocarttext',$options)) {
            $options['addtocarttext']='Add to Cart';
        }
        //if (!array_key_exists('offerstext',$options)) {
        //    $options['offerstext']='Make an Offer';
        //}
        if (!array_key_exists('adminaddnote',$options)) {
            $options['adminaddnote']='PLEASE NOTE: Offer based on [currency] currency';
        }
        if (!array_key_exists('admineditnote',$options)) {
            $options['admineditnote']='PLEASE NOTE: Offer based on [currency] price of [price]';
        }
        if (!array_key_exists('storefrontnotes',$options)) {
            $options['storefrontnotes']="<h2>Make Offer</h2><div class='make-offer-form-intro-text'>To make an offer please complete the form below:</div>PLEASE NOTE: Offer based on [currency] price of [price]";
        }
        if (!array_key_exists('toolowmsg',$options)) {
            $options['toolowmsg']='Thank you for your offer, but it is too low. Please increase the amount to submit your offer.';
        }
        if (!array_key_exists('hideaddtocart',$options)) {
            $options['hideaddtocart']='0';
        }
        if (!array_key_exists('setqtyto1',$options)) {
            $options['setqtyto1']='0';
        }
        if (!array_key_exists('autoassign',$options)) {
            $options['autoassign']='0';
        }
        if (!array_key_exists('exitonly',$options)) {
            $options['exitonly']='0';
        }
        if (!array_key_exists('autoaccept',$options)) {
            $options['autoaccept']='0';
        }
        if (!array_key_exists('acceptpercent',$options)) {
            $options['acceptpercent']='90';
        }
        if (!array_key_exists('autodecline',$options)) {
            $options['autodecline']='0';
        }
        if (!array_key_exists('declinepercent',$options)) {
            $options['declinepercent']='80';
        }
        //if (!array_key_exists('defaultcurrency',$options)) {
        //    $options['defaultcurrency']='USD';
        //}
        \update_option('intrackt_offers',$options);

        /*
         * Define the options form
         */
        \register_setting( 'intrackt_offers_options', 'intrackt_offers_options', array( 'Intrackt\Offers\PageMain', 'optionsValidate' ) );
        
        \add_settings_section('intrackt_offers_p01s01', '', array( 'Intrackt\Offers\PageMain', 'page01Section01Text' ), 'intrackt_offers_p01');
        //\add_settings_field('intrackt_offers_linktext', 'Make an Offer Link Text', array( 'Intrackt\Offers\PageMain', 'optionsLinkText' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        \add_settings_field('intrackt_offers_addtocarttext', 'Add to Cart Button Text', array( 'Intrackt\Offers\PageMain', 'optionsAddToCartText' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        //\add_settings_field('intrackt_offers_offerstext', 'Make an Offer Cart Button Text', array( 'Intrackt\Offers\PageMain', 'optionsOffersText' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        \add_settings_field('intrackt_offers_hideaddtocart', 'Hide Add to Cart Button', array( 'Intrackt\Offers\PageMain', 'optionsHideAddToCart' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        \add_settings_field('intrackt_offers_setqtyto1', 'Set customer offer to qty=1 and hide field', array( 'Intrackt\Offers\PageMain', 'optionsSetQtyTo1' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        \add_settings_field('intrackt_offers_adminaddnote', 'Admin note when adding Offer', array( 'Intrackt\Offers\PageMain', 'optionsAdminAddNote' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        \add_settings_field('intrackt_offers_admineditnote', 'Admin note when editing Offer', array( 'Intrackt\Offers\PageMain', 'optionsAdminEditNote' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        \add_settings_field('intrackt_offers_storefrontnotes', 'Top of store-front Make an Offer Form', array( 'Intrackt\Offers\PageMain', 'optionsStorefrontNotes' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        \add_settings_field('intrackt_offers_toolowmsg', 'Msg to display when offer is too low (blank if permit submission)', array( 'Intrackt\Offers\PageMain', 'optionsTooLowMsg' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        \add_settings_field('intrackt_offers_autoassign', 'Automatically assign to new products', array( 'Intrackt\Offers\PageMain', 'optionsAutoAssign' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        \add_settings_field('intrackt_offers_exitonly', 'Show offer form when exiting page', array( 'Intrackt\Offers\PageMain', 'optionsExitOnly' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        \add_settings_field('intrackt_offers_autoaccept', 'Enable auto-accept percentage for new products', array( 'Intrackt\Offers\PageMain', 'optionsAutoAccept' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        \add_settings_field('intrackt_offers_acceptpercent', 'Specify the auto-accept percentage to use', array( 'Intrackt\Offers\PageMain', 'optionsAcceptPercent' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        \add_settings_field('intrackt_offers_autodecline', 'Enable auto-decline percentage for new products', array( 'Intrackt\Offers\PageMain', 'optionsAutoDecline' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');
        \add_settings_field('intrackt_offers_declinepercent', 'Specify the auto-decline percentage to use', array( 'Intrackt\Offers\PageMain', 'optionsDeclinePercent' ), 'intrackt_offers_p01', 'intrackt_offers_p01s01');

        //\add_settings_section('intrackt_offers_p02s01', '', array( 'Intrackt\Offers\PageMain', 'page02Section01Text' ), 'intrackt_offers_p02');
        //\add_settings_field('intrackt_offers_defaultcurrency', 'Website default currency', array( 'Intrackt\Offers\PageMain', 'optionsDefaultCurrency' ), 'intrackt_offers_p02', 'intrackt_offers_p02s01');
        //PageLog::updateTestLog("PageMain::optionsDefine: end");
        
    }

    /*
     * Process page
     */
    public static function processPage() {
        
        //PageLog::updateTestLog("PageMain::processPage: start");

        /*
         * If doing full reset
         */
        if (array_key_exists('FullReset',$_POST)) {
            
            /*
             * clear the log
             */
            PageLog::resetLog();
            
            PageLog::updateLog("PageMain::processPage: Starting to do full reset");

        }
        
    }

    /*
     * Display the page
     */
    public static function displayPage() {
        
        //PageLog::updateTestLog("PageMain::displayPage: start");
        
        ?>

        <div class="wrap">
        <h2 style="display: none">       <h2>
        <h1>Intrackt Offers</h1>

        <!--
        - options form
        -->
        <form action="options.php" method="post" id="intrackt_options_form">
            
        <?php

        /*
         * if booster multi-currency active, we will display those options
         */
        //$showBooster=" display: none;";
        //if (\is_plugin_active('woocommerce-jetpack/woocommerce-jetpack.php'))
        //    if (get_option('wcj_multicurrency_enabled','no')=='yes') 
        //        $showBooster="";
        
        /*
         * Define and display settings
         */
        \settings_fields('intrackt_offers_options');
        \do_settings_sections('intrackt_offers_p01');
        
        //echo "<p> </p>";
        //echo "<div style='background-color: #f8f8f8; padding: 8px; border-color: black; border-width: 2px; border-style: solid;{$showBooster}'>";
        //\do_settings_sections('intrackt_offers_p02');
        //echo "</div>";

        ?>

        <p> </p>
        <input name="Submit" type="submit" value="<?esc_attr_e('Save Changes','Intrackt'); ?>" />
        </form>
        <script>
            /*
             * control all offer options form client-side logic
             */
            document.addEventListener("DOMContentLoaded", processOffersOptionsForm);
            function processOffersOptionsForm() {
                
                /*
                 * handle unload with changes made
                 */
                {
                    /*
                     * the form
                     */
                    myForm=document.getElementById('intrackt_options_form');
                    
                    /*
                     * intercept submits so that they do not count
                     */
                    myForm.addEventListener('submit',settingsSubmitted);
                    
                    /*
                     * intercept all changes
                     */
                    formElements=myForm.elements;
                    for (i=0;i<formElements.length;i++)
                        formElements[i].addEventListener('change',handleChanges);
                    
                    /*
                     * the unload handler itself
                     */
                    window.addEventListener("beforeunload",handleUnload);
                }
                
            }
            
            /*
             * intercept submit event
             */
            var formSubmitted=false;
            function settingsSubmitted(e) {
                formSubmitted=true;
            }
            
            /*
             * intercept all changes
             */
            var formChanges=false;
            function handleChanges(e) {
                formChanges=true;
            }
            
            /*
             * Handle unload of page
             */
            function handleUnload(e) {
                
                /*
                 * if form being submitted, skip unload message
                 */
                if (formSubmitted) return undefined;
                
                /*
                 * if no changes
                 */
                if (!formChanges) return undefined;
                
                /*
                 * we need to warn the user
                 */
                returnMsg="You have made changes to your settings.  Are you sure you want to abandon those changes?";
                (e||window.event).returnValue=returnMsg;
                return returnMsg;
            }
            
        </script>
        <?php

        /*
         * If test mode, allow additional options
         */
        if (INTRACKT_OFFERS_TESTMODE) {
            ?>
            <!--
            - Do a full reset to the plugin-- start as if initially installed
            -->
            <form action="#" method="post">
                <input type="hidden" name="action" value="PageMainProcessPage">
                <p>Test Mode is enabled.  Do you want to do a complete retest of start up?</p>
                <p>The plugin will behave as if it has been installed for the first time</p>
                <p><input type="submit" name="FullReset" value="Perform Full Reset of Plugin"></p>
            </form>
            <?php
        }

        ?>
        </div>
        <?php
    }    
    
 }

/*
 * If not during gathering the code
 */
//PageLog::updateTestLog("PageMain executed");
     
/*
 * Process any forms on this page
 */
if (isset($_POST['action'])) {
    if ($_POST['action']=="PageMainProcessPage") {
       PageMain::processPage();
    }
}
