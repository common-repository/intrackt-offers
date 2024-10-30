<?php
namespace Intrackt\Offers;
/*
 * The PageLog class will display any log messages generated by the Offers plugin
 */
class PageLog {

    /*
     * Have we instantiated the class-- this is a singleton and does not produce children
     */
	private static $initiated = false;
    
    /*
     * Init class
     */
	public static function init() {
		if ( ! self::$initiated ) {
    		self::$initiated = true;
		}
        
	}

    /*
     * Display the page
     */
    public static function displayPage() {
        
        /*
         * Get up to 1000 entries with the latest date
         */
        global $wpdb;
        $tname=$wpdb->prefix.'intrackt_log';
        
        /*
         * get last entry
         */
        $mostRecent=$wpdb->get_var("
            SELECT inlog_timestamp
            FROM {$tname}
            WHERE inlog_plugin = '".INTRACKT_PLUGIN_NAME_OFFERS."'
            ORDER BY inlog_timestamp DESC
            LIMIT 1
            ");
        
        /*
         * get all entries within a minute of this one.
         */
        $results=$wpdb->get_results("
            SELECT inlog_timestamp, inlog_message
            FROM {$tname}
            WHERE inlog_plugin = '".INTRACKT_PLUGIN_NAME_OFFERS."'
                AND TIMESTAMPDIFF(MINUTE,inlog_timestamp,'{$mostRecent}') < 1
            ORDER BY inlog_id ASC
            LIMIT 1000
            ",
            \ARRAY_A);
        
        ?>
        <div class="wrap">
            
        <h2>Offers Plugin Status</h2>
        
        <?php
        /*
        if (array_key_exists("cronsuccesstime",$options)) {
            ?>
            <p>Last successful run of scheduled task (US C<?= (date("I")==0)?"S":"D" ?>T): <?= date('Y-m-d H:i:s', $options['cronsuccesstime']-((6-date("I"))*3600)) ?></p>
            <?php
        } else {
            ?>
            <p>The scheduled task has not yet run.</p>
            <?php
        }
        */
        ?>

        <h2>Offers Process Log</h2>

        <table>
        <?php
        foreach ($results as $row) {
            ?>
            <tr>
                <td><?=$row['inlog_timestamp']?></td>
                <td><?=$row['inlog_message']?></td>
            </tr>
            <?php
        }
        ?>
        </table>
        </div>
        <?php
    }    

    /*
     * Reset log
     */
    public static function resetLog() {
        
        /*
         * Add Intrackt log table to DB if it doesn't exist.
         */
        global $wpdb;
        $tname=$wpdb->prefix.'intrackt_log';
        $result=$wpdb->query(
            "CREATE TABLE IF NOT EXISTS ".$tname." ( ".
                "inlog_id int NOT NULL AUTO_INCREMENT, ".
                "inlog_timestamp TIMESTAMP, " .
                "inlog_plugin VARCHAR(32), ".
                "inlog_test bit(1), ".
                "inlog_message VARCHAR(4096), ".
                "PRIMARY KEY (inlog_id), ".
                "INDEX (inlog_timestamp), ".
                "INDEX (inlog_plugin) ".
                ")"
            );
        
        /*
         * Report error to error log
         */
        //if ($result != 1) {
            if ( is_array( $result ) || is_object( $result ) ) {
                error_log( print_r( $result, true ) );
            } else {
                error_log( $result );
            }
        //}
        
        
        /*
         * Then delete all thr log entries for this module
         */
        $result=$wpdb->query(
            "DELETE FROM ".$tname." ".
                "WHERE inlog_plugin='".INTRACKT_PLUGIN_NAME_OFFERS."'"
            );
        
    }
    
    /*
     * Update log
     */
    public static function updateLog($message) {
        
        global $wpdb;
        $tname=$wpdb->prefix.'intrackt_log';
        $result=$wpdb->query($wpdb->prepare(
            "INSERT INTO ".$tname." ".
                "(inlog_timestamp,inlog_plugin,inlog_test, inlog_message) ".
                "VALUES (CURRENT_TIMESTAMP,%s,0,%s)"
            ,INTRACKT_PLUGIN_NAME_OFFERS,$message));
    }    

    /*
     * Update log  (test values)
     */
    public static function updateTestLog($message) {

        /*
         * Add test log entries only if in test mode
         */
        if (INTRACKT_OFFERS_TESTMODE) {
            
        global $wpdb;
        $tname=$wpdb->prefix.'intrackt_log';
        //$message=str_replace("'","\\'",substr($message,0,4096));
        $result=$wpdb->query($wpdb->prepare(
            "INSERT INTO ".$tname." ".
                "(inlog_timestamp,inlog_plugin,inlog_test,inlog_message) ".
                "VALUES (CURRENT_TIMESTAMP,%s,0,%s)"
            ,INTRACKT_PLUGIN_NAME_OFFERS,$message));
            
        }
    }    

    /*
     * Update test log displaying an object
     */
    public static function updateTestObjectLog($messagePrefix,$object,$idx=0) {

        /*
         * Add test log entries only if in test mode
         */
        if (INTRACKT_OFFERS_TESTMODE) {
                                    
            /*
             * if an object, do me on each element
             */
            if (\is_array($object)) {
                
                foreach ($object as $objectKey=>$objectValue) {
                    self::updateTestObjectLog("{$messagePrefix}['{$objectKey}']",$objectValue,$idx+1);
                }
                
            /*
             * if an object, do me on each element
             */
            } elseif (\is_object($object)) {
                
                foreach ($object as $objectKey=>$objectValue) {
                    self::updateTestObjectLog("{$messagePrefix}->{$objectKey}",$objectValue,$idx+1);
                }
                
            /*
             * else do this scalor
             */
            } elseif ($idx==0) {
            
                self::updateTestLog("{$messagePrefix}.{$object}");
                
            } else {
            
                self::updateTestLog("{$messagePrefix}='{$object}'");
                
            }
            
        }
    }    
    
}
