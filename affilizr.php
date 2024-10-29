<?php
/*
Plugin Name: Affilizr Official Plugin
Plugin URI: http://www.affilizr.com
Description: This plugin will automatically add your Affilizr script to your website pages.
Author: Affilizr
Version: 1.0
Author URI: http://www.affilizr.com
*/

//install/uninstall function calls
register_activation_hook( __FILE__, 'affilizr_install' );
register_uninstall_hook( __FILE__, 'affilizr_uninstall' );

add_action( 'admin_menu', 'affilizr_pages' );
add_action( 'wp_footer', 'affilizrAdsscript' );

function affilizr_install() {
	add_option( 'affilizr_status', '1' );
}

function affilizr_uninstall() {
	delete_option( 'affilizr_status' );
	delete_option( 'affilizr_publisherid' );
	delete_option( 'affilizr_websiteid' );
}


// action function for above hook
function affilizr_pages() {
    add_options_page( 'Affilizr', 'Affilizr Settings', 'manage_options', 'affilizr-admin', 'affisettingoptions_page' );
}

//plugin settings page
function affisettingoptions_page() {

	//save plugin settings
	if( isset( $_POST['btnSave'] ) ) {

		//check nonce for security
		check_admin_referer( 'affilizr_plugin_save' );

		$affilizr_status = ( isset( $_POST['affilizr_status'] ) ) ? $_POST['affilizr_status'] : 0;

		update_option( 'affilizr_status', absint( $affilizr_status ) );
		update_option( 'affilizr_publisherid',  $_POST['affilizr_publisherid']  );
		//update_option( 'affilizr_websiteid', absint( $_POST['affilizr_websiteid'] ) );

		echo '<div id="message" class="updated">Settings saved successfully</div>';

	}

	//reset plugin settings
	if( isset( $_POST['btnReset'] ) ) {

		//check nonce for security
		check_admin_referer( 'affilizr_plugin_save' );

		//set options back to specified defaults
		update_option( 'affilizr_status', 1 );
		echo '<div id="message" class="updated">Settings reset successfully</div>';
	}

	//load setting values
	$affilizr_status = get_option( 'affilizr_status' );
	$affilizr_publisherid = get_option( 'affilizr_publisherid' );
?>
<style type="text/css">
.small_txt {
	font-size:0.85em;
	color:#898989;
	font-family:Verdana,sans-serif;
}
h2 {
	font-family:Georgia,"Times New Roman","Bitstream Charter",Times,serif;
	font-size:24px;
	font-size-adjust:none;
	font-stretch:normal;
	font-style:italic;
	font-variant:normal;
	font-weight:normal;
	line-height:35px;
	margin:0;
	padding:14px 15px 3px 0;
	text-shadow:0 1px 0 #FFFFFF;
}
</style>
<form method="post" name="frm_affilizr" id="frm_affilizr">
	<?php wp_nonce_field( 'affilizr_plugin_save' ); ?>
  <input type="hidden" id="affiid" name="affiid" value="" />
  <div style="float:left; width:40%;"><table border="0" width="100%">
    <tr>
      <td><table width="95%">
          <tr>
            <td colspan="2"><h2>Affilizr Official Plugin</h2></td>
          </tr>
          <tr>
            <td colspan="2">This plugin will automatically add your Affilizr script to your website pages</td>
          </tr>
          <tr>
            <td colspan="2" height="30">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" width="200">Affilizr is:</td>
			<td>
				<input type="radio" name="affilizr_status" value="1" <?php checked( $affilizr_status, 1 ); ?> /> On <br />
				<input type="radio" name="affilizr_status" value="0" <?php checked( $affilizr_status, 0 ); ?> /> Off
			</td>
          </tr>
          <tr>
            <td colspan="2" height="20">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><label for="publisherid">Publisher ID :</label></td>
            <td>
				<input type="text" name="affilizr_publisherid" id="affilizr_publisherid"  value="<?php echo esc_attr( $affilizr_publisherid ); ?>"/><br />
				<span class="small_txt">Please enter your affilizr pid</span>
			</td>
          </tr>
		
        </table></td>
     
    </tr>
    <tr>
      <td colspan="2">To view or edit your Affilizr account settings, please visit your <a href="http://publisher.affilizr.com/" target="_blank">Publisher account</a> <br />
        and <a href="http://affilizr.com/faq.html" target="_blank">our FAQs</a>, or contact us at <a href="mailto:hello@affilizr.fr">hello@affilizr.fr</a> </td>
    </tr>
    <tr>
      <td colspan="2" height="20">&nbsp;</td>
    </tr>
    <tr>
		<td colspan="2" height="40">
			<input type="submit" class="button-primary" name="btnSave" value="Save Settings" />
			<input type="submit" class="button-secondary" name="btnReset" value="Reset to Default" />
		</td>
    </tr>
  </table>
  </div>
 
 
</form>
<?php	
}

//add the script in footer
function affilizrAdsscript() {
	$display = 0;
	//to check Affilizr enable or disable
	 $options = absint( get_option( 'affilizr_status' ) );
	$affiscript= '';

	 if( $options == 1 ){
			 $display =1;
	 }
	
	 if( $display == 1 ){
	  $affiscript = '
		<!-- Affilizr START -->
		<script type="text/javascript">
		   var affilizr_cid = "'. get_option( 'affilizr_publisherid' ) .'"; 
		   var affilizr_wsid = 0;
		</script>
		<script type="text/javascript" src="http://script.affilizr.com/js/affilizr.js"></script>
		<!-- Affilizr END -->';
	 }
	 echo $affiscript;
	
}
?>