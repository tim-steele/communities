<?php 
/*************************************
 * Content, Class, and Query Filters *
 *************************************/

/**
 * Do not call this function directly, add it to the wp_nav_menu filter
 * Adds .first-menu-item and .last-menu-item to menu.
 * 
 * @param type $output
 * @return type 
 */
function add_menu_class_first_last($output) {
  $output = preg_replace('/class="menu-item/', 'class="first-menu-item menu-item', $output, 1);
  //$output = substr_replace($output, 'class="last-menu-item menu-item last-child', strripos($output, 'class="menu-item'), strlen('class="menu-item'));
  return $output;
}
add_filter('wp_nav_menu', 'add_menu_class_first_last');


/**
 * Do not call this function directly, add it to the body_class filter
 * 
 * Conditionally adds classes to the body class of a page for styling purposes.
 * These examples are from the Kmart Fashion and BirthdayClub themes.
 * 
 * @author Eddie Moya
 * 
 * @param type $classes
 * @return array 
 */
function filter_body_class($classes) {
    
    /**
     * Modify Styles pages on theme options. This example is from Kmart Fashion
     */
    $options = get_option('theme_options');
    
    if(isset($options['brand'])){
        $classes[] = $options['brand'];
    }
    
    if (is_category())
        $classes[] = get_queried_object()->category_nicename;
    
    if (is_page())
        $classes[] = 'page-' .get_queried_object()->post_name;

     if ('section' == get_post_type())
        $classes[] = 'section';
    
    return $classes;
}
add_filter('body_class', 'filter_body_class');



/**
 * Do not call this function directly, add it to the request filter
 * 
 * Modifies the original WP_Query so that we dont have to continuously re-query 
 * with query_posts from within templates. 
 * 
 * Consider also the 'pre_get_posts', and 'parse_query' filters. As well as
 * other query filters explained in the WP_Query codex page.
 * 
 * @author Eddie Moya
 * 
 * @global WP_Query $wp_query
 * @param WP_Query $query_string
 * @return modified WP_Query object
 */
function custom_primary_query($query = '') {

    /**
     * This is being used for the results list widget.
     */
    if(isset($query->query_vars['is_widget']) && isset($_REQUEST['widget'])){
        if ($query->query_vars['is_widget']['widget_name']== 'results-list' && $_REQUEST['widget'] == 'results-list') {

            $category = (isset($_REQUEST['filter-sub-category'])) ? $_REQUEST['filter-sub-category'] : $_REQUEST['filter-category'];

            unset($query->query_vars['cat']);
            $query->set('cat', $category);
            $query->set('category__in', array($category));
        }
    }

    // if(!empty($query->query_vars['category_name']) && !empty($query->query_vars['post_type']) ){
    //     $query->is_category = false;
    // }
    //return $query;
}
add_action('pre_get_posts', 'custom_primary_query');



/******************************************
 * END  Content, Class, and Query Filters *
 ******************************************/
add_filter( 'widget_form_callback', 'widget_form_extend', 10, 2);
add_filter( 'dynamic_sidebar_params', 'dynamic_sidebar_params' );
add_filter( 'widget_update_callback', 'widget_update', 10, 2 );



function widget_form_extend( $instance, $widget ) {

    if(get_class($widget) == 'WP_Widget_Links'){

        if(!isset($instance['classes'])){
            $instance['classes'] = null;
            $row = '';
            $row .= "<p>\n";
            $row .= "\t<label for='widget-{$widget->id_base}-{$widget->number}-sub-title'>Sub Title:</label>\n";
            if(isset($instance['sub-title'])){
                $row .= "\t<input type='text' name='widget-{$widget->id_base}[{$widget->number}][sub-title]' id='widget-{$widget->id_base}-{$widget->number}-sub-title' class='widefat' value='{$instance['sub-title']}'/>\n";
            }
            $row .= "</p>\n";

            echo $row;
        }
    }
    return $instance;
}





function dynamic_sidebar_params( $params ) {
    global $wp_registered_widgets;
    $widget_id  = $params[0]['widget_id'];
    $widget_obj = $wp_registered_widgets[$widget_id];
    $widget_opt = get_option($widget_obj['callback'][0]->option_name);
    $widget_num = $widget_obj['params'][0]['number'];

    $opts = $widget_opt[$widget_num];
    
    //Links Widget (built-in)
    if($widget_obj['name'] == 'Links'){
        $params[0]['after_title'] = 
        "\n\t<h4>".$opts['sub-title']
        ."</h4>".$params[0]['after_title']
        ."\n\t<section class='content-body clearfix'>";

        $params[0]['after_widget'] = 
            '</section>'
            .$params[0]['after_widget'];    
    }
    return $params;
}



function widget_update( $instance, $new_instance ) {
    $instance['sub-title'] = $new_instance['sub-title'];
    return $instance;
}






/**
 * Allows periods to be passed as part of a user slug.
 *
 * @author Eddie Moya, Dan Crimmins
 */
function sanitize_title_with_dots_and_dashes($title, $raw_title = '', $context = 'display') {
    $title = strip_tags($title);
    // Preserve escaped octets.
    $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
    // Remove percent signs that are not part of an octet.
    $title = str_replace('%', '', $title);
    // Restore octets.
    $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

    if (seems_utf8($title)) {
        if (function_exists('mb_strtolower')) {
            $title = mb_strtolower($title, 'UTF-8');
        }   
        $title = utf8_uri_encode($title, 200);
    }

    $title = strtolower($title);
    $title = preg_replace('/&.+?;/', '', $title); // kill entities
    if( 'query' == $context ){ 
        $title = str_replace('.', '-', $title);
    }

    if ( 'save' == $context ) {
        // nbsp, ndash and mdash
        $title = str_replace( array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title );
        // iexcl and iquest
        $title = str_replace( array( '%c2%a1', '%c2%bf' ), '', $title );
        // angle quotes
        $title = str_replace( array( '%c2%ab', '%c2%bb', '%e2%80%b9', '%e2%80%ba' ), '', $title );
        // curly quotes
        $title = str_replace( array( '%e2%80%98', '%e2%80%99', '%e2%80%9c', '%e2%80%9d' ), '', $title );
        // copy, reg, deg, hellip and trade
        $title = str_replace( array( '%c2%a9', '%c2%ae', '%c2%b0', '%e2%80%a6', '%e2%84%a2' ), '', $title );
    }

    $title = preg_replace( ( 'query' == $context ) ? '/[^%a-z0-9 ._-]/' : '/[^%a-z0-9 _-]/' , '', $title);
    $title = preg_replace('/\s+/', '-', $title);
    $title = preg_replace('|-+|', '-', $title);
    $title = trim($title, '-');

    return $title;
}
remove_filter('sanitize_title', 'sanitize_title_with_dashes');
add_filter('sanitize_title', 'sanitize_title_with_dots_and_dashes', 10, 3);




add_action('template_redirect', 'template_check');
function template_check(){
    $pt = get_query_var('post_type');


    if((!is_widget() && is_category() && $pt != 'section') || (is_post_type_archive(array('guide', 'question')) || $pt == 'post' )){

        $templates = array();

        if(is_category()){
            $templates[] = 'archive-tax-'.$pt.'.php';
            $templates[] = 'archive-tax.php';
        }

        $templates[] = 'archive-'.$pt.'.php';
        $templates[] = "archive.php";
        $template = get_query_template($template_name, $templates);
        //echo "<pre>";print_r($templates);echo "</pre>";
        include( $template );
        exit;
    } 

    
}


add_action('init', 'catch_cookies');
function catch_cookies(){
    //echo "<pre>";print_r($_COOKIE);echo "</pre>";
}


// add_filter('widgetpress_widget_classname', 'featured_guide_class_filter');
// function featured_guide_class_filter($classname){
//     if($classname == 'featured-guide') {
//         $classname = 'featured-post';
//     }
//     return $classname;
// }

// add_filter('widgetpress_pre_add_classes', 'featured_question_class_filter');
// function featured_question_class_filter($params){
//     global $wp_registered_widgets;

//     $widget_id  = $params[0]['widget_id'];
//     $widget_obj = $wp_registered_widgets[$widget_id];
//     $widget_opt = get_option($widget_obj['callback'][0]->option_name);
//     $widget_num = $widget_obj['params'][0]['number'];
//     $widget = $widget_opt[$widget_num];

//     echo "<pre>";print_r($widget_obj);echo "</pre>";

//     return $params;
//}






