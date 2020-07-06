<?php 



    /*
     *  APi
     * 
     */
    add_action('reset_pass_api','reset_pass_api_fun');
    function reset_pass_api_fun($password){
            /*
            $data_string = array(
                "password" => $password,               
            );
            json_encode($data_string);

         
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, 'https://staging.sandboxsmart.com/api/authn/updatePassword');
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
            */
            // return $return_content;

           
            if($return_code ==200){
                $obj = json_decode($return_content);
                if($obj->status==1){                    
                    $path = wc_get_account_endpoint_url( 'myaccount_reset_pass')."?status=1";
                    ?>
                    <script>window.location.href = '<?php echo $path; ?>';</script>
                    <?php
                };               
               ?>
               <?php
            }else{
                
                if($obj->status==1){                    
                    $path = wc_get_account_endpoint_url( 'myaccount_reset_pass')."?status=0";
                    ?>
                     <script>window.location.href = '<?php echo $path; ?>';</script>
                    <?php
                };    
            }
            // return $return_code;
    }


    ?>