<?php
/*   
    remove  checkout field
*/





add_filter( 'woocommerce_checkout_fields', 'misha_email_first' );
 
function misha_email_first( $fields ) {     

         // Billing fields
    unset( $fields['billing']['billing_company'] );
    unset( $fields['billing']['billing_state'] );
    unset( $fields['billing']['billing_last_name'] );
    unset( $fields['billing']['billing_address_2'] );
    // unset( $fields['billing']['billing_postcode'] );

    // Shipping fields
    unset( $fields['shipping']['shipping_company'] );
    // unset( $fields['shipping']['shipping_phone'] );
    unset( $fields['shipping']['shipping_state'] );
    unset( $fields['shipping']['shipping_last_name'] );
    unset( $fields['shipping']['shipping_address_2'] );


    $fields['billing']['billing_first_name']['class'] = array('form-row-wide');
    $fields['shipping']['shipping_first_name']['class'] = array('form-row-wide');

    $fields['billing']['billing_city']['priority'] = 4;

     
  


	return  $fields;
}


?>