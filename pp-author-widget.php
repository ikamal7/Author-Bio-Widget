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

//$author_bio_widget = new Author_Bio_Widget();

function register_author_widget() {
    register_widget( 'Author_Bio_Widget' );
}
 add_action( 'widgets_init', 'register_author_widget' );

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
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_author_assets' ) );

    }
    public function frontend_author_assets()
    {
        wp_enqueue_style('author-widget', plugin_dir_url( __FILE__ ) . 'css/author-widget.css',null,time(),'all');
        wp_enqueue_style('fontawesome-main', plugin_dir_url( __FILE__ ) . 'css/fontawesome.min.css',null,'5.13','all');
        wp_enqueue_style('fontawesome-brand', plugin_dir_url( __FILE__ ) . 'css/brands.min.css',null,'5.13','all');
    }
    public function author_assets() {
        wp_enqueue_script( 'media-upload' );

        wp_enqueue_media();
        wp_enqueue_script( 'author-media-upload', plugin_dir_url( __FILE__ ) . 'js/author-media-upload.js', array( 'jquery' ) );

    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        $image = !empty( $instance['image'] ) ? $instance['image'] : '';
        $name = !empty( $instance['name'] ) ? $instance['name'] : '';
        $about = !empty( $instance['about'] ) ? $instance['about'] : '';
        $template = !empty( $instance['template'] ) ? $instance['template'] : '';
        $position = !empty( $instance['position'] ) ? $instance['position'] : '';
        $social_icons = array(
            "facebook",
            "twitter",
            "github",
            "pinterest",
            "instagram",
            "youtube",
            "vimeo",
            "tumblr",
            "dribbble",
            "flickr",
            "behance"
        );
        echo $args['before_widget'];
        if ( !empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        } 
        ?>
        <div class="<?php esc_attr_e($template); ?>">
        <?php
        if ( $image ): ?>
            <img src="<?php echo esc_url( $image ); ?>" alt="">
         <?php endif; ?>
         <h3><?php echo $name; ?>
            <span><?php echo $position; ?></span>
        </h3>
         <?php echo wpautop($about); ?>
            <ul>
                <?php
                foreach ( $social_icons as $sci ) {
                    $url = trim( $instance[ $sci ] );
                    if ( ! empty( $url ) ) {
                        if ( $sci == "vimeo" ) {
                            $sci = "vimeo-square";
                        }
                        $sci = esc_attr( $sci );
                        echo "<li><a target='_blank' href='" . esc_attr( $url ) . "'><i class='fab fa-" . esc_attr( $sci ) . "'></i></a></li>";
                    }
                }
                ?>
            </ul>
        </div>
         <?php
        echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
        
        $title        = !empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'About me', 'author-bio' );
        $image        = !empty( $instance['image'] ) ? $instance['image'] : '';
        $name        = !empty( $instance['name'] ) ? $instance['name'] : '';
        $about        = !empty( $instance['about'] ) ? $instance['about'] : '';
        $template        = !empty( $instance['template'] ) ? $instance['template'] : '';
        $position        = !empty( $instance['position'] ) ? $instance['position'] : '';
        $social_icons = array(
            "facebook",
            "twitter",
            "github",
            "pinterest",
            "instagram",
            "google-plus",
            "youtube",
            "vimeo",
            "tumblr",
            "dribbble",
            "flickr",
            "behance",
        );
        foreach ( $social_icons as $sc ) {
            if ( !isset( $instance[$sc] ) ) {
                $instance[$sc] = "";
            }
        }
        $templates = array(
            'defatult' =>__('Default','author-bio' ),
            'template_1' =>__('Template 1','author-bio' ),
            'template_2' =>__('Template 2','author-bio' ),
            'template_3' =>__('Template 3','author-bio' ),
            'template_4' =>__('Template 4','author-bio' ),
        );
       
       

        ?>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'author-bio' );?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>		
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php esc_attr_e( 'Name:', 'author-bio' );?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name' ) ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>">
		</p>		
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'position' ) ); ?>"><?php esc_attr_e( 'Position:', 'author-bio' );?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'position' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'position' ) ); ?>" type="text" value="<?php echo esc_attr( $position ); ?>">
		</p>

        <p>
            <label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Image:', 'author-bio' );?></label>
            <img src="<?php echo $image; ?>" width="100%">
            <input name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" class="widefat" type="text"  value="<?php echo esc_url( $image ); ?>" />
            <input class="upload_image_button button-primary" type="button" value="Upload Image" />
        </p>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'about' ) ); ?>"><?php esc_attr_e( 'About:', 'author-bio' );?></label>
        <textarea name="<?php echo esc_attr( $this->get_field_name( 'about' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'about' ) ); ?>" cols="40" rows="5"><?php echo esc_html( $about ); ?></textarea>

        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>"><?php esc_attr_e( 'Select Template:', 'author-bio' );?></label>
        <select name="<?php echo esc_attr( $this->get_field_name( 'template' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>">
            <?php 
            foreach ($templates as $tkey => $template_name) {
               printf('<option value="%1$s" %2$s>%3$s</option>', esc_attr($tkey), selected($tkey, $template, true), $template_name);
            }
            ?>
        </select>
        </p>

        </p>
        <?php foreach ( $social_icons as $sci ) {
            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( $sci ) ); ?>"><?php esc_attr_e( ucfirst( $sci ) . " " . __( 'URL', 'author-bio' ) );?>
                    : </label>
                <br/>

                <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( $sci ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( $sci ) ); ?>"
                       value="<?php echo esc_attr( $instance[$sci] ); ?>"/>
            </p>

            <?php
}

    }

    /**
     * Processing widget options on save
     *
     * @param  array   $new_instance The new options
     * @param  array   $old_instance The previous options
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance                = array();
        $instance['title']       = ( !empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['name']       = ( !empty( $new_instance['name'] ) ) ? sanitize_text_field( $new_instance['name'] ) : '';
        $instance['about']       = ( !empty( $new_instance['about'] ) ) ? sanitize_textarea_field( $new_instance['about'] ) : '';
        $instance['template']       = ( !empty( $new_instance['template'] ) ) ? sanitize_key( $new_instance['template'] ) : '';
        $instance['position']       = ( !empty( $new_instance['position'] ) ) ? sanitize_text_field( $new_instance['position'] ) : '';
        $instance['image']       = ( !empty( $new_instance['image'] ) ) ? esc_url( $new_instance['image'] ) : '';
        $instance['facebook']    = ( !empty( $new_instance['facebook'] ) ) ? esc_url( $new_instance['facebook'] ) : '';
        $instance['twitter']     = ( !empty( $new_instance['twitter'] ) ) ? esc_url( $new_instance['twitter'] ) : '';
        $instance['github']      = ( !empty( $new_instance['github'] ) ) ? esc_url( $new_instance['github'] ) : '';
        $instance['pinterest']   = ( !empty( $new_instance['pinterest'] ) ) ? esc_url( $new_instance['pinterest'] ) : '';
        $instance['instagram']   = ( !empty( $new_instance['instagram'] ) ) ? esc_url( $new_instance['instagram'] ) : '';
        $instance['youtube']     = ( !empty( $new_instance['youtube'] ) ) ? esc_url( $new_instance['youtube'] ) : '';
        $instance['vimeo']       = ( !empty( $new_instance['vimeo'] ) ) ? esc_url( $new_instance['vimeo'] ) : '';
        $instance['tumblr']      = ( !empty( $new_instance['tumblr'] ) ) ? esc_url( $new_instance['tumblr'] ) : '';
        $instance['dribbble']    = ( !empty( $new_instance['dribbble'] ) ) ? esc_url( $new_instance['dribbble'] ) : '';
        $instance['flickr']      = ( !empty( $new_instance['flickr'] ) ) ? esc_url( $new_instance['flickr'] ) : '';
        $instance['behance']     = ( !empty( $new_instance['behance'] ) ) ? esc_url( $new_instance['behance'] ) : '';

        return $instance;

    }
}


