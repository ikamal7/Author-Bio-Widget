<?php

/*
 * Plugin Name: Author Bio Widget
 * Plugin URI: https://kamal.pw/author-bio-widget
 * Description:This plugin help for blogger show their bio in sidebar
 * Version: 1.0
 * Author: Kamal Hosen
 * Author URI: https://kamal.pw
 * Text Domain: author-bio
 * Domain Path: /languages/
 * License: GNU General Public License v2 or later
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

class Author_Bio_Widget extends WP_Widget {
    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        $widget_ops = array(
            'classname'   => 'author-bio-widget',
            'description' => __( 'Author Bio Widget', ' author-bio' ),
        );
        parent::__construct( 'author_bio', __( 'Author Bio', ' author-bio' ), $widget_ops );
        add_action( 'admin_enqueue_scripts', array( $this, 'author_assets' ) );

    }
    public function author_assets() {
        wp_enqueue_script( 'media-upload' );
        
        wp_enqueue_media();
        wp_enqueue_script( 'author-media-upload', plugin_dir_url( __FILE__ ) . 'author-media-upload.js', array( 'jquery' ) );
       
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        $image = ! empty( $instance['image'] ) ? $instance['image'] : '';
        echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
         if($image): ?>
            <img src="<?php echo esc_url($image); ?>" alt="">
         <?php endif; 
		echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
        print_r($instance);
        $title = !empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'About me', 'author-bio' );
        $image = !empty(  $instance['image']) ? esc_url( $instance['image']): '';

        ?>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'author-bio' );?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

        <p>
            <label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Image:','author-bio' );?></label>
            <img src="<?php echo $image; ?>" width="100%">
            <input name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" class="widefat" type="text"  value="<?php echo esc_url( $image ); ?>" />
            <input class="upload_image_button button-primary" type="button" value="Upload Image" />
        </p>
        <?php
}

    /**
     * Processing widget options on save
     *
     * @param  array   $new_instance The new options
     * @param  array   $old_instance The previous options
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['image'] = ( ! empty( $new_instance['image'] ) ) ? esc_url( $new_instance['image'] ) : '';

        return $instance;
       
    }
}

//$author_bio_widget = new Author_Bio_Widget();

function register_author_widget() {
    register_widget( 'Author_Bio_Widget' );
}
add_action( 'widgets_init', 'register_author_widget' );
