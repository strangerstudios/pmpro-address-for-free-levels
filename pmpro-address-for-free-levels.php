<?php
/*
Plugin Name: PMPro Address For Free Levels
Plugin URI: http://www.paidmembershipspro.com/wp/pmpro-address-for-free-levels/
Description: Show address fields for free levels also with Paid Memberships Pro
Version: .2.1
Author: Stranger Studios
Author URI: http://www.strangerstudios.com
*/
 
/*
  Show address fields for free levels too.
  Add this code to your active theme's functions.php or a custom plugin.
*/
//keep address fields shown on checkout page
function pmproaffl_pmpro_checkout_boxes_require_address()
{
	//don't force show them on review page
	global $pmpro_review;
	if($pmpro_review)
		return;
?>
<script>
	var pmpro_show_billing_address_fields_timer;
	function showBillingAddressFields()
	{
		jQuery('#pmpro_billing_address_fields').show();
		pmpro_show_billing_address_fields_timer = setTimeout(function(){showBillingAddressFields();}, 200);
	}
	jQuery(document).ready(function() {
		//show it and keep showing it
		showBillingAddressFields();
		
		<?php
			//remove billing from address title if the level is free
			global $pmpro_level;
			if(pmpro_isLevelFree($pmpro_level))
			{
			?>
				//change heading
				jQuery('#pmpro_billing_address_fields th').html('Address');
			<?php
			}
		?>
	});
</script>
<?php
}
add_action("pmpro_checkout_boxes", "pmproaffl_pmpro_checkout_boxes_require_address");
 
 
 
//make sure address fields are required
function pmproaffl_pmpro_required_user_fields($fields)
{	
	global $bfirstname, $blastname, $baddress1, $bcity, $bstate, $bzipcode, $bcountry, $bphone, $bemail;
	
	$fields["bfirstname"] = $bfirstname;
	$fields["blastname"] = $blastname;
	$fields["baddress1"] = $baddress1;
	$fields["bcity"] = $bcity;
	$fields["bstate"] = $bstate;
	$fields["bzipcode"] = $bzipcode;
	$fields["bphone"] = $bphone;
	$fields["bemail"] = $bemail;
	$fields["bcountry"] = $bcountry;	
 
	return $fields;
}
add_action("pmpro_required_user_fields", "pmproaffl_pmpro_required_user_fields");

//save fields in session for PayPal Express/etc
function pmproaffl_pmpro_paypalexpress_session_vars()
{	
	//assume the request is set
	$_SESSION['bfirstname'] = $_REQUEST['bfirstname'];
	$_SESSION['blastname'] = $_REQUEST['blastname'];
    $_SESSION['baddress1'] = $_REQUEST['baddress1'];	
    $_SESSION['bcity'] = $_REQUEST['bcity'];
    $_SESSION['bstate'] = $_REQUEST['bstate'];
	$_SESSION['bzipcode'] = $_REQUEST['bzipcode'];
	$_SESSION['bphone'] = $_REQUEST['bphone'];		
	$_SESSION['bemail'] = $_REQUEST['bemail'];		
	$_SESSION['bcountry'] = $_REQUEST['bcountry'];		
	
	//check this one cause it's optional
	if(!empty($_REQUEST['baddress2']))
		$_SESSION['baddress2'] = $_REQUEST['baddress2'];
	else
		$_SESSION['baddress2'] = "";
		
	//if there is a user here, save in user meta as well
	global $current_user;
	if(!empty($current_user->ID))
	{
		//save billing info ect, as user meta
		$meta_keys = array("pmpro_bfirstname", "pmpro_blastname", "pmpro_baddress1", "pmpro_baddress2", "pmpro_bcity", "pmpro_bstate", "pmpro_bzipcode", "pmpro_bcountry", "pmpro_bphone", "pmpro_bemail");
		$meta_values = array($_REQUEST['bfirstname'], $_REQUEST['blastname'], $_REQUEST['baddress1'], $_REQUEST['baddress2'], $_REQUEST['bcity'], $_REQUEST['bstate'], $_REQUEST['bzipcode'], $_REQUEST['bcountry'], $_REQUEST['bphone'], $_REQUEST['bemail']);
		pmpro_replaceUserMeta($current_user->ID, $meta_keys, $meta_values);
	}
}
add_action("pmpro_paypalexpress_session_vars", "pmproaffl_pmpro_paypalexpress_session_vars");

//Load fields from session if available.
function pmproaffl_init_load_session_vars($param)
{
	//check that no field values were passed in and that we have some in session
	if(empty($_REQUEST['bfirstname']) && !empty($_SESSION['bfirstname']))
	{
		$_REQUEST['bfirstname'] = $_SESSION['bfirstname'];
		$_REQUEST['blastname'] = $_SESSION['blastname'];
		$_REQUEST['baddress1'] = $_SESSION['baddress1'];
		$_REQUEST['baddress2'] = $_SESSION['baddress2'];
		$_REQUEST['bcity'] = $_SESSION['bcity'];
		$_REQUEST['bstate'] = $_SESSION['bstate'];
		$_REQUEST['bzipcode'] = $_SESSION['bzipcode'];
		$_REQUEST['bphone'] = $_SESSION['bphone'];
		$_REQUEST['bemail'] = $_SESSION['bemail'];
		$_REQUEST['bcountry'] = $_SESSION['bcountry'];		
	}
	
	return $param;
}
add_action('init', 'pmproaffl_init_load_session_vars', 5);

//after checkout, clear any session vars
function pmproaffl_pmpro_after_checkout()
{
	$vars = array('bfirstname', 'blastname', 'baddress1', 'baddress2', 'bcity', 'bstate', 'bzipcode', 'bphone', 'bemail', 'bcountry');
	foreach($vars as $var)
	{
		if(isset($_SESSION[$var]))
			unset($_SESSION[$var]);
	}	
}
add_action("pmpro_after_checkout", "pmproaffl_pmpro_after_checkout");	