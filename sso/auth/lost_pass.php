






<?php 


    /**
     *      1 => success , 0 => error 
     *      
     *  
     */
    function printMsg($type, $msg=""){

        $class="";
        $title="";
       
        switch($type){
            case 1:
                $class = 'message';
                $title="更新成功";             
            break;

            case 2:
                $class = 'error';
                $title="發生錯誤";
            break;
        }
        ?>
			<div class="woocommerce"><ul class="woocommerce-<?php echo $class; ?>" role="alert">
						<li><strong><?php echo $title; ?></strong>  <?php echo $msg; ?></li></ul>
			</div>	
        <?php
    }




    /*
     *  APi
     * 
     */
    add_action('lost_pass_api','lost_pass_api_fun');
    function lost_pass_api_fun($email){
       
            $data_string = array(
                "email" => $email,               
            );
            json_encode($data_string);

         
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, 'https://staging.sandboxsmart.com/api/authn/sendForgotpasswordMail');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_string));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen(json_encode($data_string)))
            );
            ob_start();
            curl_exec($ch);
            $return_content = ob_get_contents();
            ob_end_clean();
            $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   

            // return $return_content;

           
            if($return_code ==200){
                $obj = json_decode($return_content);
                if($obj->status==1){                    
                    $path = wc_get_account_endpoint_url( 'lost-password')."?status=1";
                    ?>
                    <script>window.location.href = '<?php echo $path; ?>';</script>
                    <?php
                };               
               ?>
               <?php
            }else{
                
                if($obj->status==1){                    
                    $path = wc_get_account_endpoint_url( 'lost-password')."?status=0";
                    ?>
                     <script>window.location.href = '<?php echo $path; ?>';</script>
                    <?php
                };    
            }
            // return $return_code;
    }

?>