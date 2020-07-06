<?php 

function my_custom_my_account_menu_items( $items ) {
    // Remove the logout menu item.
    $logout = $items['customer-logout'];
    unset( $items['customer-logout'] );
     
    // Insert your custom endpoint.
    $items['myaccount_reset_pass'] = __( '變更密碼', 'woocommerce' );
     
    // Insert back the logout item.
    $items['customer-logout'] = $logout;
     
    return $items;
    }
     
    add_filter( 'woocommerce_account_menu_items', 'my_custom_my_account_menu_items' );
     
    /**
    * Register new endpoint to use inside My Account page.
    *
    * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
    */
    function myaccount_reset_pass_endpoints() {
        add_rewrite_endpoint( 'myaccount_reset_pass', EP_ROOT | EP_PAGES );
    }
     
    add_action( 'init', 'myaccount_reset_pass_endpoints' );
     
    /**
    * Add new query var.
    *
    * @param array $vars
    * @return array
    */
    function my_custom_query_vars( $vars ) {
    $vars[] = 'myaccount_reset_pass';
     
    return $vars;
    }
     
    add_filter( 'query_vars', 'my_custom_query_vars', 0 );
     



    /**
    * Endpoint HTML content.
    */
    function my_custom_endpoint_content() {       

        /*
        if(isset($_POST['password_1']) && $_POST['password_1']!=""){
            if(email_exists($_POST['user_login_api'])){				
                     do_action('lost_pass_api', $_POST['user_login_api']);				 			
                ?>	
                <?php
            }else{
                printMsg(2,'請提供一個有效的電子郵件信箱。');          
            }
        }
        */
        
        $error = array();


        

        if(empty($_POST['password_1'])){
            $error[] = "請填寫新密碼。";           
        }

        if(empty($_POST['password_2'])){           
            $error[] = "請填寫確認新密碼。";           
        }
        
        if($_POST['password_1'] != $_POST['password_2']){
            $error[] = "新密碼 與 確認新密碼不相同。";         
        }
        
        $out = valid_newpass($_POST['password_1']);
        if(count($error)==0 && $out){
            $error[] = $out;      
        }
        
        


        if(count($error)==0){
            printMsg(1,'');
        }else{
            foreach ($error as $item) {
                $error_msg .= '<li>'.$item.'</li>';
            }
            $error_msg = '<ul>'.$error_msg.'</ul>';
            printMsg(2, $error_msg);
        }


        ?>
        <form method="post" class="woocommerce-ResetPassword lost_reset_password"  action="<?php  echo wc_get_account_endpoint_url( 'myaccount_reset_pass'); ?>">
      
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			    <label for="password_1"><?php esc_html_e( 'New password', 'woocommerce' ); ?></label>
			    <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off"  value="<?php echo $_POST['password_1']; ?>" />
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
                <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" value="<?php echo  $_POST['password_2']; ?>" />
            </p>
            <button type="submit" class="woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>

        </form>
        <?php
    }
     
    add_action( 'woocommerce_account_myaccount_reset_pass_endpoint', 'my_custom_endpoint_content' );