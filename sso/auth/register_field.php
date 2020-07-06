<?php 



add_action( 'woocommerce_register_form', 'misha_add_register_form_field' );
 
function misha_add_register_form_field(){

    
    ?>

    <script>
    (function($){
        $(document).ready(function(){
           
                $('.aloha-dropdown select').select2({ minimumResultsForSearch: -1 }); // Add your target select field
        });
    })(jQuery);
    </script>
    
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password">密碼&nbsp;<span class="required">*</span></label>
				<span class="password-input">
                    <span class="show-password-input"></span>
                    <input class="woocommerce-Input woocommerce-Input--text input-text"
                                             type="password" 
                                             name="password" 
                                             id="password2" 
                                             autocomplete="current-password" value="<?php echo $_POST['password']; ?>" />
                </span>
		</p>        
    <?php   
      
        woocommerce_form_field(
            'myname',
            array(
                'type'        => 'text',
                'required'    => true, 
                'label'       => '姓名',
                'class' => array('form-row-first'),
                'id'=> "myname"
            ),
            ( isset($_POST['myname']) ? $_POST['myname'] : '' )
        );

        woocommerce_form_field(
            'sex',
            array(
                'type'          => 'select',                
                'label'       => '密碼',
                'class' => array('form-row-last aloha-dropdown clearfix'),
                'options'		=> array(
                                            '男' => '男',
                                            '女' => '女'
                                        ),
                // 'id'=> "password2"
            ),
            ( isset($_POST['sex']) ? $_POST['sex'] : '' )
        );

        
    
        wp_enqueue_script( 'wc-country-select' );    
        woocommerce_form_field( 'billing_country', array(
            'type'      => 'country',
            'label'       => '國家',
            'class'     => array('chzn-drop form-row-wide '),
            'label'     => __('Country'),
            'placeholder' => __('Choose your country.'),
            'required'  => true,
            'clear'     => true
        ));

        woocommerce_form_field(
            'address',
            array(
                'type'        => 'text',
                'required'    => true, 
                'label'       => '地址',
                'class' => array('form-row-full'),
                'id'=> "myname"
            ),
            ( isset($_POST['address']) ? $_POST['address'] : '' )
        );        

        woocommerce_form_field(
            'mobile',
            array(
                'type'        => 'text',
                'required'    => true, 
                'label'       => '手機號碼',
                'class' => array('form-row-full'),
                'id'=> "myname"
            ),
            ( isset($_POST['mobile']) ? $_POST['mobile'] : '' )
        );   

}










/*   驗證密碼  */
add_action( 'woocommerce_register_post', 'misha_validate_fields', 10, 3 );
function misha_validate_fields( $username, $email, $errors ) {     
    if ( empty( $_POST['password'] ) ) {
        $errors->add( 'password_error', '請輸入密碼' );
        return false;
    }
 
    if(valid_newpass($_POST['password'])!=0){       
        $errors->add( 'password_error', valid_newpass($_POST['password']) );
    }else{
        echo "<h1>asdas</h1>";
        $errors->add( 'password_error', valid_newpass($_POST['password']) );
    };
  
    
    // return false;
}
    

