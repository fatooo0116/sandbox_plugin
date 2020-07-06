<?php


/* ============================================= ========================================= */
/* 最新文章表 */
add_action( 'rest_api_init', function () {
    register_rest_route( 'news/v1', '/latest_post', array(
      'methods' => 'POST',
      'callback' => 'get_latest_post_func',
    ) );
  });
  
  
  
  function get_latest_post_func($data){
   
    /*  設置  */   
    $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 2;
   //  $lang = (isset($data['lang'])) ? $data['lang'] : "en";
     $skip = (isset($data['skip'])) ? $data['skip'] : 0;  
   //  do_action( 'wpml_switch_language', $lang );
   
   
        $args = array(
          'post_type' => array('post'),
          'post_status' => array('publish'),
          'posts_per_page' => $post_per_page, 
          'order' => 'DESC',
          'orderby' => 'date', 
          'offset' => $skip
        );
  
        $data_item = array();
        
        $the_query = new WP_Query( $args );
        // The Loop
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $tems = get_the_terms(get_the_ID(),'category');
                
                foreach($tems as $item) {
                    $item->link = get_term_link($item->term_id,'category');
                }
                
  
                $post_item = array(
                  'id' => get_the_ID(),
                  'title' => get_the_title(),           
                  'excerpt' => get_the_excerpt(get_the_ID()),
                  'link' => get_permalink(),
                  'date'=>get_the_date('Y-m-d m:i:s'),
                  'img'=> get_the_post_thumbnail_url(get_the_ID()),
                  'img_s'=> get_the_post_thumbnail_url(get_the_ID(),'medium'),
                  'cat'=>$tems
                );
  
                
                /*
                $the_content = apply_filters('the_content', get_the_content());
                if ( !empty($the_content) ) {
                  $post_item['content'] = $the_content;
                }
                */
  
                $data_item[] = $post_item;
            }
        } else {
          // no posts found
      }
  
    return $data_item;
  }
  


/* ============================================= ========================================= */
/* 最新消息 單一文章內容的取得 */
add_action( 'rest_api_init', function () {
    register_rest_route( 'news/v1', '/post', array(
      'methods' => 'POST',
      'callback' => 'get_post_func',
    ) );
  });

  function get_post_func($data){
    
      $post_id = (isset($data['post_id'])) ? $data['post_id'] : 0;
      // $lang = (isset($data['lang'])) ? $data['lang'] : "en";    
      // do_action( 'wpml_switch_language', $lang );
      
          $args = array(
            'p' => (int)$post_id
          );
          $data_item = "";
          
          $the_query = new WP_Query( $args );
          // The Loop
          if ( $the_query->have_posts() ) {
              while ( $the_query->have_posts() ) {
                  $the_query->the_post();

                  $tems = get_the_terms(get_the_ID(),'category');                  
                  foreach($tems as $item) {
                      $item->link = get_term_link($item->term_id,'category');
                  }
                  
    
                  $post_item = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),           
                    'excerpt' => get_the_excerpt(get_the_ID()),
                    'link' => get_permalink(),
                    'date'=>get_the_date('Y-m-d m:i:s'),
                    'img'=> get_the_post_thumbnail_url(get_the_ID()),
                    'img_s'=> get_the_post_thumbnail_url(get_the_ID(),'medium'),
                    'cat'=>$tems
                  );
    
                  
                  
                  $the_content = apply_filters('the_content', get_the_content());
                  if ( !empty($the_content) ) {
                    $post_item['content'] = $the_content;
                  }
                  
                  $prev_post = get_previous_post();
                  if (!empty($prev_post)){
                    $post_item['pre_post'] = array(
                      'link' => $prev_post->guid,
                      'title' => $prev_post->post_title
                    );
                  }

                  $next_post = get_next_post();
                  if (!empty($next_post)){
                    $post_item['next_post'] = array(
                      'link' => $prev_post->guid,
                      'title' => $prev_post->post_title
                    );
                  }

                  $post_item['comments_number'] = get_comments_number(get_the_ID());

    
                  return $post_item;; 
              }
          } else { 
            
          /*  nothing */ 
          return 0; 
        }

       // return $data_item;   
    // return $data_items;
  }





/* ============================================= ========================================= */
/*  最新消息關鍵字搜尋  */
add_action( 'rest_api_init', function () {
    register_rest_route( 'news/v1', '/search', array(
      'methods' => 'POST',
      'callback' => 'search_fun',
    ) );
  });
  
  
  function search_fun($data){
    $text = (isset($data['text'])) ? $data['text'] : '';
    
   
   //  do_action( 'wpml_switch_language', $lang );
    $text = str_replace(' ', '+', $text);
    $url = home_url("/wp-json/wp/v2/search/?search=".$text);
   
    $ch = curl_init();
     
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
    $output = curl_exec($ch);
    $output = json_decode($output);
  
    
    $out = array();
    foreach($output  as $item){
      $xtems = get_the_terms($item->id,'category');                  
      foreach($xtems as $xitem) {
          $xitem->link = get_term_link($xitem->term_id,'category');
      }
      if(get_post_type($item->id)=='post'){
        $out[] = array(
            'id' => $item->id,
            // 'post_type'=> get_post_type($item->id),
            'title' => $item->title,
            'url' => $item->url,
            'img' => get_the_post_thumbnail_url($item->id),
            'cat' => $xtems,
            'excerpt'=>get_the_excerpt($item->id) 
          );
      }
    }
    
     
    curl_close($ch);
     
    return  $out;
  }
  




/* ============================================= ========================================= */
/* 取得最新消息類別 */
  add_action( 'rest_api_init', function () {
    register_rest_route( 'news/v1', '/terms', array(
      'methods' => 'POST',
      'callback' => 'get_post2_func',
    ) );
  } );
  
  function get_post2_func( $data ) {

   //  $lang = (isset($data['lang'])) ? $data['lang'] : "en";

    global $sitepress;   
   //  $sitepress->switch_lang($lang); // Switch to new language    
    $terms = get_terms( array(
      'taxonomy' => 'category',
      'hide_empty' => false,
    )); 
 
    return $terms;
  } 


/* ============================================= ========================================= */
/* 某類別文章 */
add_action( 'rest_api_init', function () {
    register_rest_route( 'news/v1', '/latest_post_by_term', array(
      'methods' => 'POST',
      'callback' => 'latest_post_by_term',
    ) );
  });
  
  
  function latest_post_by_term($data){
      
  
    /*  設置  */   
    $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 2;
    $term_id = (isset($data['term_id'])) ? $data['term_id'] : 0;
    $skip = (isset($data['skip'])) ? $data['skip'] : 0;
  
   
    
        $args = array(
          'post_type' => array('post'),
          'post_status' => array('publish'),
          'posts_per_page' => $post_per_page, 
          'order' => 'DESC',
          'orderby' => 'date', 
          'offset' => $skip
        );
  
        if($term_id){
          $args['cat'] = $term_id;
        }
  
        $data_item = array();
        
        $the_query = new WP_Query( $args );
        // The Loop
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $tems = get_the_terms(get_the_ID(),'category');
                
                foreach($tems as $item) {
                    $item->link = get_term_link($item->term_id,'category');
                }
                
  
                $author_id = $post->post_author;
  
                $post_item = array(
                  'id' => get_the_ID(),
                  'title' => get_the_title(),           
                  'excerpt' => get_the_excerpt(get_the_ID()),
                  'link' => get_permalink(),
                  'date'=>get_the_date('Y-m-d m:i:s'),
                  'img'=> get_the_post_thumbnail_url(get_the_ID()),
                  'img_s'=> get_the_post_thumbnail_url(get_the_ID(),'medium'),
                  'cat'=>$tems,
                  'author'=> get_the_author_meta( 'display_name' , $author_id )
                );
  
                
                /*
                $the_content = apply_filters('the_content', get_the_content());
                if ( !empty($the_content) ) {
                  $post_item['content'] = $the_content;
                }
                */
  
                $data_item[] = $post_item;
            }
        } else {
          // no posts found
      }
  
    return $data_item;
  }
  
 



/* ============================================= ========================================= */

/* 單一文章留言 */
add_action( 'rest_api_init', function () {
    register_rest_route( 'news/v1', '/comments_by_post', array(
      'methods' => 'POST',
      'callback' => 'comments_by_post',
    ) );
  });
  
  
  function comments_by_post($data){
      
  
    /*  設置  */   
    $post_id = (isset($data['post_id'])) ? $data['post_id'] : 2;
    
    $comments = get_comments( array( 'post_id' => $post_id ) );
  
    /*
    $outputComments = array();
    foreach($comments as $comment){
      $outputComments[]  = $comment;
      $comment->avatar = get_avatar_url($comment->user_id);
      if($comment->comment_parent !=0){
  
      } 
    }
    */
  
  
      $output = array();
      $all = array();
      $dangling = array();
  
  
      foreach ($comments as $entry) {
  
        $temp  = array(
          "comment_ID"=> $entry->comment_ID,
          "comment_post_ID" => $entry->comment_post_ID,
          "comment_author" => $entry->comment_author,
          "comment_author_email" => $entry->comment_author_email,
          "comment_date" => $entry->comment_date,
          "comment_content"=> $entry->comment_content,
          "comment_parent" => $entry->comment_parent,
          "avatar" => get_avatar_url($comment->user_id),
          'likes'=> get_comment_meta( $entry->comment_ID, '_commentliked', true ),
          "children" => array() 
        );
        $id = $entry->comment_ID;
       
        if($entry->comment_parent == "0") {
           $all[$id] = $temp;
          $output[] =& $all[$id];  
        } else {
           $dangling[$id] = $temp;
        }
      }
  
      
      while (count($dangling) > 0) {
        foreach($dangling as $entry) {
            $id = $entry['comment_ID'];
            $pid = $entry['comment_parent'];
  
  
            if (isset($all[$pid])) {            
                $all[$id] = $entry;
                $all[$pid]['children'][] =& $all[$id]; 
                unset($dangling[$entry['comment_ID']]);
            }
        }
      }
      
  
  
  
    return  $output;
  }
  

?>