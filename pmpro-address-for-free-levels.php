<?php
/*
Plugin Name: Paid Memberships Pro - Address For Free Levels Add On 
Plugin URI: https://www.paidmembershipspro.com/add-ons/capture-name-address-free-levels-offsite-gateway/
Description: Show address fields for free levels also with Paid Memberships Pro
Version: 0.5
Author: Paid Memberships Pro
Author URI: https://www.paidmembershipspro.com/
*/
 
/**
 * Keep address fields shown on checkout page
 */
function pmproaffl_pmpro_checkout_boxes_require_address() {
	//don't force show them on review page
	global $pmpro_review;		
    ?>
    <script>
    	var pmpro_show_billing_address_fields_timer;
    	function showBillingAddressFields() {
    		<?php if(empty($pmpro_review)) { ?>
    			jQuery('#pmpro_billing_address_fields').show();
    		<?php } else { ?>
    			jQuery('#pmpro_billing_address_fields').hide();	//hiding when on the review page
    		<?php } ?>
    		
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
add_action( "pmpro_checkout_boxes", "pmproaffl_pmpro_checkout_boxes_require_address" );

/**
 * Make sure we include address fields (for post 1.8)
 */
function pmproaffl_init_include_address_fields_at_checkout() {
	add_filter( 'pmpro_include_billing_address_fields', '__return_true' );
}
add_action( 'init', 'pmproaffl_init_include_address_fields_at_checkout', 30 );
 
/**
 * Make sure address fields are required
 */
function pmproaffl_pmpro_required_billing_fields( $fields ) {
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
add_action("pmpro_required_billing_fields", "pmproaffl_pmpro_required_billing_fields", 30);

/**
 * Sanitize and save billing fields from $_REQUEST
 */
function pmproaffl_save_billing_fields_from_request( $user_id ) {
    $meta_keys = array( "bfirstname", "blastname", "baddress1", "baddress2", "bcity", "bstate", "bzipcode", "bcountry", "bphone", "bemail" );
    
    // grab the data from $_REQUEST
    $meta_values = array();
    foreach( $meta_keys as $key ) {
        if ( ! empty( $_REQUEST[$key] ) ) {
            $meta_values[] = sanitize_text_field( $_REQUEST[$key] );
        } else {
            $meta_values[] = '';
        }
    }
    
    // Need prefixes before saving. Cheaper than str_replacing when grabbing from $_REQUEST
    foreach( $meta_keys as $key => $value ) {
        $meta_keys[$key] = 'pmpro_' . $value;
    }
    
    pmpro_replaceUserMeta( $user_id, $meta_keys, $meta_values );
}

/**
 * Save fields in session for PayPal Express/etc
 */
function pmproaffl_pmpro_paypalexpress_session_vars() {	
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
	if(!empty($_REQUEST['baddress2'])) {
		$_SESSION['baddress2'] = $_REQUEST['baddress2'];
	} else {
		$_SESSION['baddress2'] = "";
	}
    
	//if there is a user here, save in user meta as well
	global $current_user;
	if( ! empty( $current_user->ID ) ) {
		pmproaffl_save_billing_fields_from_request( $current_user->ID );
	}
}
add_action("pmpro_paypalexpress_session_vars", "pmproaffl_pmpro_paypalexpress_session_vars");
add_action("pmpro_before_send_to_twocheckout", "pmproaffl_pmpro_paypalexpress_session_vars", 10, 2);

/**
 * Update user meta before changing membership level.
 * Gateways like PayPal Standard redirect after this,
 * so we need to update the user now.
 * Needs to run before priority 10.
 */
function pmproaffl_pmpro_checkout_before_change_membership_level( $user_id, $morder ) {    
    if( ! empty( $user_id ) ) {
        pmproaffl_save_billing_fields_from_request( $user_id );
    }
}
add_action( 'pmpro_checkout_before_change_membership_level', 'pmproaffl_pmpro_checkout_before_change_membership_level', 5, 2);

/**
 * Load fields from session if available.
 */
function pmproaffl_init_load_session_vars( $param ) {
	//check that no field values were passed in and that we have some in session
	if(empty($_REQUEST['bfirstname']) && !empty($_SESSION['bfirstname'])) {
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
add_action( 'pmpro_checkout_preheader', 'pmproaffl_init_load_session_vars', 5 );

/**
 * Add address fields to the order for free checkouts.
 */
function pmproaffl_pmpro_checkout_order_free($morder) {
	if(empty($morder)) {
		$morder = new MemberOrder();
    }

	if(empty($morder->billing)) {
		$morder->billing = new stdClass();
    }
    
	$morder->billing->name = sanitize_text_field( $_REQUEST['bfirstname'] . " " . $_REQUEST['blastname'] );
	$morder->billing->street = sanitize_text_field( $_REQUEST['baddress1'] . " " . $_REQUEST['baddress2'] );
	$morder->billing->city = sanitize_text_field( $_REQUEST['bcity'] );
	$morder->billing->state = sanitize_text_field( $_REQUEST['bstate'] );
	$morder->billing->zip = sanitize_text_field( $_REQUEST['bzipcode'] );
	$morder->billing->phone = sanitize_text_field( $_REQUEST['bphone'] );
	$morder->billing->country= sanitize_text_field( $_REQUEST['bcountry'] );
	
	return $morder;
}
add_filter( 'pmpro_checkout_order_free', 'pmproaffl_pmpro_checkout_order_free' );

/**
 * After checkout, clear any session vars.
 */
function pmproaffl_pmpro_after_checkout() {
	$vars = array( 'bfirstname', 'blastname', 'baddress1', 'baddress2', 'bcity', 'bstate', 'bzipcode', 'bphone', 'bemail', 'bcountry' );
	foreach($vars as $var)
	{
		if(isset($_SESSION[$var]))
			unset($_SESSION[$var]);
	}	
}
add_action("pmpro_after_checkout", "pmproaffl_pmpro_after_checkout");

/**
 * Add links to the plugin row meta
 */
function pmproaffl_plugin_row_meta($links, $file) {
	if(strpos($file, 'pmpro-address-for-free-levels.php') !== false) {
		$new_links = array(
			'<a href="' . esc_url('https://www.paidmembershipspro.com/add-ons/capture-name-address-free-levels-offsite-gateway/')  . '" title="' . esc_attr( __( 'View Documentation', 'pmpro' ) ) . '">' . __( 'Docs', 'pmpro' ) . '</a>',
			'<a href="' . esc_url('https://paidmembershipspro.com/support/') . '" title="' . esc_attr( __( 'Visit Customer Support Forum', 'pmpro' ) ) . '">' . __( 'Support', 'pmpro' ) . '</a>',
		);
		$links = array_merge($links, $new_links);
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'pmproaffl_plugin_row_meta', 10, 2 );	