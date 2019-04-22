<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/jacm365
 * @since      1.0.0
 *
 * @package    Bam_Basic_Ad_Manager
 * @subpackage Bam_Basic_Ad_Manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Bam_Basic_Ad_Manager
 * @subpackage Bam_Basic_Ad_Manager/admin
 * @author     Jaime Clavijo <jacm365@gmail.com>
 */
class Bam_Basic_Ad_Manager_Admin {

    /**
     * Default post type for bam ads.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $post_type    Default post type for bam ads.
     */
    private $post_type = 'bam_ad';
    
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bam-basic-ad-manager-admin.css', array(), $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bam-basic-ad-manager-admin.js', array( 'jquery' ), $this->version, false );
    }

    /**
     * Register the initial functions for the admin area.
     *
     * @since    1.0.0
     */
    public function init() {
        add_menu_page( 'BAM Basic Ad Manager', 'BAM Ads', 'manage_options', 'bam_basic_ad_manager', array($this, 'get_main_view'), 'dashicons-media-document', 100 );
        add_submenu_page( 'bam_basic_ad_manager', 'BAM Basic Ad Manager', 'BAM Ads', 'manage_options', 'bam_basic_ad_manager', array($this, 'get_main_view'), 'dashicons-media-document', 1 );
        add_submenu_page( 'bam_basic_ad_manager', 'BAM Basic Ad Manager - Add new', 'Add new', 'manage_options', 'bam_basic_ad_manager_add_new', array($this, 'get_new_ad_view'), 'dashicons-plus', 2 );
    }

    /**
     * Handle the shortcode
     *
     * @since   1.0.0
     */
    public function handle_ad_template($attributes, $content = null) {
        global $post;
        
        $ad_data = get_post( $attributes['id'] );
        $meta_data = json_decode($ad_data->post_content);
        
        $rem_time_data = $this->get_time_data( $ad_data->post_date_gmt, $meta_data );
                
        $bam_ad_data = array(
            'title' => $ad_data->post_title,
            'type' =>  $meta_data->type,
            'remaining_time' => $rem_time_data,
            'bgcolor' => $meta_data->bgcolor,
            'template' => $meta_data->template
        );
        
        $referrer_categories = get_the_category( $post->ID );
        $bam_ad_data['bgcolor'] = $this->get_bgcolor( get_the_category( $post->ID ), $bam_ad_data['bgcolor'] );
        
        ob_start();        
        echo '<link rel="stylesheet" type="text/css" media="all" href="' . plugin_dir_url( __FILE__ ) . 'ad-templates/css/' . strtolower($bam_ad_data['template']) . '.css' . '"/>';
        require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/ad-templates/' . strtolower($bam_ad_data['template']) . '.php';
        
        return ob_get_clean();
    }

    /**
     * Get ad bgcolor.
     *
     * @since    1.0.0
     * @param    array     $referrer_categories  Post date string.
     * @param    object    $meta_data      Post content object.
     */
    public function get_bgcolor( $referrer_categories, $original ) {
        foreach ($referrer_categories as $category) {
            if ($category->name == 'NFL' || $category->slug == 'NFL') {
                $original = 'black';
            } elseif ($category->name == 'NBA' || $category->slug == 'NBA') {
                $original = 'orange';
            } elseif ($category->name == 'MLB' || $category->slug == 'MLB') {
                $original = 'blue';
            }
        }

        return $original;
    }

    /**
     * Get the time data formated.
     *
     * @since    1.0.0
     * @param    string    $post_date_gmt  Post date string.
     * @param    object    $meta_data      Post content object.
     */
    public function get_time_data( $post_date_gmt, $meta_data ) {
        $rem_time_data = array();

        if ( $meta_data->type == 'pick' ) {
            
            $current_time = time();
            $ad_time = strtotime( $post_date_gmt );
            $time_difference = $current_time - $ad_time;
            $remainingt_chunks = explode(':', $meta_data->remaining_time);
            $remainingt_secs = $remainingt_chunks[0] * 3600 + $remainingt_chunks[1] * 60 + $remainingt_chunks[2];
            $actual_remaining_time = $remainingt_secs - $time_difference;

            if ($actual_remaining_time <= 0) {
                $rem_time_data = array(
                    'days' => '00', 
                    'hours' => '00', 
                    'min' => '00', 
                    'sec' => '00' 
                );
            } else {
                $days = floor($actual_remaining_time/3600/24);
                $actual_remaining_time = $actual_remaining_time - ($days * 3600 * 24);
                $hours = floor($actual_remaining_time/3600);
                $actual_remaining_time = $actual_remaining_time - ($hours * 3600);
                $minutes = floor($actual_remaining_time / 60);
                $seconds = $actual_remaining_time - ($minutes * 60);

                $rem_time_data = array(
                    'days' => sprintf('%02d', $days), 
                    'hours' => sprintf('%02d', $hours), 
                    'min' => sprintf('%02d', $minutes), 
                    'sec' => sprintf('%02d', $seconds) 
                );
            }

        }

        return $rem_time_data;
    }
    
    /**
     * Get the main view file for the admin area.
     *
     * @since    1.0.0
     */
    public function get_main_view() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/main.php';
    }

    /**
     * Get the main view file for the admin area.
     *
     * @since    1.0.0
     */
    public function get_new_ad_view() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/form.php';
    }

    /**
     * Register links for the plugin admin options.
     *
     * @since    1.0.0
     */
    public function register_links($links) {
        $links[] = '<a href="admin.php?page=bam_basic_ad_manager">Settings</a>';
        return $links;
    }

    /**
     * Saves the ad to database in the wp_posts table
     *
     * @since    1.0.0
     *
     * @param    array   $args  All of the ad data.
     */
    public function save_ad($args) {
        
        $content = array(
            'type' => $args['type'],
            'remaining_time' => $args['remaining_time'],
            'bgcolor' => $args['bgcolor'],
            'template' => $args['template'] 
        );

        $post_content = json_encode( $content );

        if ( $args['post_id'] == -1 ) {
            $post_id = wp_insert_post( array(
                'post_type' => $this->post_type,
                'post_status' => 'publish',
                'post_title' => $args['title'],
                'post_content' => $post_content
            ) );
        } else {
            $post_id = wp_update_post( array(
                'ID' => $args['post_id'],
                'post_status' => 'publish',
                'post_title' => $args['title'],
                'post_content' => $post_content
            ) );
        }
        echo'<script> window.location="admin.php?page=bam_basic_ad_manager"; </script> ';
    }

    /**
     * Saves the ad to database in the wp_posts table
     *
     * @since    1.0.0
     *
     * @param    int      $post_id  Post id to delete.
     * @param    string   $nonce    Wordpress nonce.
     */
    public function delete_ad( $post_id, $nonce ) {
        if ( wp_verify_nonce( $nonce, 'delete-bam-ad_'.$post_id ) ) {
            wp_delete_post($post_id);
            echo'<script> window.location="admin.php?page=bam_basic_ad_manager"; </script> ';
        } else {
            wp_die('You are not authorized to perform this action.');
        }
    }

    /**
     * Return the ad-templates folder data.
     * 
     * @return string
     */
    function get_templates() {
        $templates = array();
        $dir = new DirectoryIterator(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/ad-templates');
        
        while($dir->valid()) {
            $file = $dir->current();
            if ( !$file->isDot() && $file->isFile() ) {
                $doc_comments = array_filter(
                    token_get_all( file_get_contents($file->getPathname()) ), function($token) {
                        return $token[0] == T_DOC_COMMENT;
                    }
                );
                $doc_comment = array_shift( $doc_comments );
                $each_comment = explode( "\n", $doc_comment[1]);
                $name = trim(explode('* Template Name:', $each_comment[1])[1]);
                $description = trim(explode('* Description:', $each_comment[2])[1]);
                array_push( $templates, array( 'name' => $name, 'description' => $description ) );
            }
            $dir->next();
        }

        return $templates;
    }

}
