



<?php 


// add_action('authenticate', 'login_auth_sandbox', 30, 2);

 add_filter( 'authenticate', 'login_auth_sandbox', 5, 1 );
function login_auth_sandbox($user) {



    
       if(isset($_POST['username']) && !empty($_POST['username'])){
            if(isset($_POST['password']) && !empty($_POST['password'])){
                $auto_data = array(
                    "email" => $_POST['username'],
                    "password" => $_POST['password']
                );


                $out = post_json_data('https://staging.sandboxsmart.com/api/authn/signInByEmail/v2',json_encode($auto_data));                
                if($out['code']!=200){
                    return new WP_Error( 'error','API Connect Error');
                }

                $result = json_decode($out['result']);
                // print_r($result);

                if($result->status==1){


                    $user_id = email_exists($auto_data['email']);
                    clean_user_cache($user_id);
                    wp_clear_auth_cookie();
                    wp_set_current_user($user_id);
                    wp_set_auth_cookie($user_id, true, false);
                        
                    $user = get_user_by('id', $user_id);
                    update_user_caches($user);


                    /*  同步相關 wordpress 密碼 */
                    /*
                    $user_id = email_exists($auto_data['email']);

                    global $wpdb;
                    $user_table = $wpdb->prefix . 'users';
                    
                    $wpdb->update( 
                        $user_table, 
                        array( 
                            'user_pass' => md5($auto_data['password']),	// string                            
                        ), 
                        array( 'ID' => $user_id ), 
                    );
                    */
                    


                    // do_action( 'wp_login',$user_id);
                 
                    
                     return $user;
                }else{
                    return new WP_Error( 'error','帳密錯誤 ');
                }

           }
       }

      

       return $user;
}