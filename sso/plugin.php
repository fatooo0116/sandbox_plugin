<?php 
/*
Plugin Name: SSO Login
Plugin URI: https://aloha-tech.com/
Description: SSO Login
Version: 1.1.0
Author: Mike Hsu
*/


/*   custom checkout field */

require "auth/register_field.php";
require "auth/login.php";
require "auth/lost_pass.php";

require "lib/lib.php";
require "lib/checkout.php";
require "lib/myaccount.php";
require "restful_api/news.php";
require "myaccount/reset_pass.php";




wp_enqueue_style( 'custom_css', plugins_url( 'assets/css/custom.css', __FILE__ ), '', rand(0,99999),  'all' );









function post_json_data($url, $data_string) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Content-Length: ' . strlen($data_string))
        );
    ob_start();
    curl_exec($ch);
    $return_content = ob_get_contents();
    ob_end_clean();
    $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);


    return array('code'=>$return_code, 'result'=>$return_content);
}








