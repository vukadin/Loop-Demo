<?php
namespace Loop;

class Plugin
{
    public function __construct()
    {
        add_action( 'init', array( $this, 'register_event_post_type' ) );
        add_action( 'init', array( $this, 'register_event_tag_taxonomy' ) );
        add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_event') );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_css' ) );
        add_shortcode( 'events_list', array( $this, 'shortcode_events_list' ) );
        add_shortcode( 'export_events', array( $this, 'shortcode_export_events' ) );
        add_action( 'wp', array( $this, 'export_events' ) );

        if( class_exists( "\WP_CLI" ) ) :
            \WP_CLI::add_command( 'import_events', array( $this, 'import_events' ) );
        endif;
    }

    public function register_event_post_type()
    {
        $labels = array(
            'name'                  => _x( 'Events', 'Post type general name', 'loop' ),
            'singular_name'         => _x( 'Event', 'Post type singular name', 'loop' ),
            'menu_name'             => _x( 'Events', 'Admin Menu text', 'loop' ),
            'name_admin_bar'        => _x( 'Event', 'Add New on Toolbar', 'loop' ),
            'add_new'               => __( 'Add New', 'loop' ),
            'add_new_item'          => __( 'Add New event', 'loop' ),
            'new_item'              => __( 'New event', 'loop' ),
            'edit_item'             => __( 'Edit event', 'loop' ),
            'view_item'             => __( 'View event', 'loop' ),
            'all_items'             => __( 'All events', 'loop' ),
            'search_items'          => __( 'Search events', 'loop' ),
            'parent_item_colon'     => __( 'Parent events:', 'loop' ),
            'not_found'             => __( 'No events found.', 'loop' ),
            'not_found_in_trash'    => __( 'No events found in Trash.', 'loop' ),
            'featured_image'        => _x( 'Event Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'loop' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'loop' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'loop' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'loop' ),
            'archives'              => _x( 'Event archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'loop' ),
            'insert_into_item'      => _x( 'Insert into event', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'loop' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this event', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'loop' ),
            'filter_items_list'     => _x( 'Filter events list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'loop' ),
            'items_list_navigation' => _x( 'Events list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'loop' ),
            'items_list'            => _x( 'Events list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'loop' ),
        );     

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'capability_type'    => 'post',
            'hierarchical'       => false,
            'menu_position'      => 20,
            'supports'           => array( 'title' )
        );
          
        register_post_type( 'event', $args );
    }

    public function register_event_tag_taxonomy()
    {    
        $labels = array(
            'name'                       => _x( 'Event tags', 'taxonomy general name', 'loop' ),
            'singular_name'              => _x( 'Event tag', 'taxonomy singular name', 'loop' ),
            'search_items'               => __( 'Search Event tags', 'loop' ),
            'popular_items'              => __( 'Popular Event tags', 'loop' ),
            'all_items'                  => __( 'All Event tags', 'loop' ),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __( 'Edit Event tag', 'loop' ),
            'update_item'                => __( 'Update Event tag', 'loop' ),
            'add_new_item'               => __( 'Add New Event tag', 'loop' ),
            'new_item_name'              => __( 'New Event tag Name', 'loop' ),
            'separate_items_with_commas' => __( 'Separate event tag with commas', 'loop' ),
            'add_or_remove_items'        => __( 'Add or remove event tag', 'loop' ),
            'choose_from_most_used'      => __( 'Choose from the most used event tag', 'loop' ),
            'not_found'                  => __( 'No event tags found.', 'loop' ),
            'menu_name'                  => __( 'Event tags', 'loop' ),
        );

        $args = array(
            'hierarchical'          => false,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
        );
    
        register_taxonomy( 'event_tag', 'event', $args );
    }

    public function register_meta_boxes()
    {
        add_meta_box( 'event-settings', __( 'Settings', 'loop' ), array( $this, 'output_event_settings_meta_box' ), 'event' );
    }

    public function output_event_settings_meta_box( $post )
    {
        $organizer = get_post_meta( $post->ID, '_event_organizer', true );
        $timestamp = get_post_meta( $post->ID, '_event_timestamp', true );
        $email = get_post_meta( $post->ID, '_event_email', true );
        $latitude = get_post_meta( $post->ID, '_event_latitude', true );
        $longitude = get_post_meta( $post->ID, '_event_longitude', true );
        $address = get_post_meta( $post->ID, '_event_address', true );
        $about = get_post_meta( $post->ID, '_event_about', true );

        $nonce = Helpers::get_event_edit_nonce( $post->ID );

        include LD_DIR.'/templates/admin/event_settings_meta_box.php';
    }

    public function save_event( $post_id )
    {
        if( defined( "DOING_AUTOSAVE") && DOING_AUTOSAVE ) return;
        if( get_post_type( $post_id ) !== 'event' ) return;
        if( !current_user_can( 'edit_post', $post_id ) ) return;

        if( !isset( $_POST['_event_nonce'] ) || !wp_verify_nonce( $_POST['_event_nonce'], Helpers::get_event_edit_nonce( $post_id ) ) ) return;

        $posted_data = array(
            '_event_organizer' => sanitize_text_field( $_POST['event']['organizer'] ),
            '_event_timestamp' => sanitize_text_field( $_POST['event']['timestamp'] ),
            '_event_email' => sanitize_text_field( $_POST['event']['email'] ),
            '_event_latitude' => floatval( $_POST['event']['latitude'] ),
            '_event_longitude' => floatval( $_POST['event']['longitude'] ),
            '_event_address' => sanitize_text_field( $_POST['event']['address'] ),
            '_event_about' => sanitize_textarea_field( $_POST['event']['about'] )
        );

        foreach( $posted_data as $meta_key => $meta_value ) :
            update_post_meta( $post_id, $meta_key, $meta_value );
        endforeach;
    }

    public function import_events()
    {
        $import_file = LD_DIR.'/data.json';
        $data = json_decode( file_get_contents( $import_file ), true );

        $imported = 0;
        $updated = 0;

        if( !empty( $data ) ) :
            foreach( $data as $item ) : 
                $post_id = Helpers::get_event_post_id( $item['id'] );
                if( !$post_id ) :
                    Helpers::create_event_post( $item );
                    $imported++;
                else : 
                    Helpers::update_event_post( $post_id, $item );
                    $updated++;
                endif;
            endforeach;
        endif;

        $message = sprintf( 
            _x( '%d event%s %s imported and %d event%s %s updated.', 'import events email notification', 'loop' ),
            $imported,
            $imported !== 1 ? 's' : '', 
            $imported !== 1 ? 'were' : 'was', 
            $updated,
            $updated !== 1 ? 's' : '',
            $updated !== 1 ? 'were' : 'was' 
        );

        Helpers::send_import_events_notification( $message );
    }

    public function enqueue_css()
    {
        wp_enqueue_style( 'loop-demo', plugins_url( 'assets/css/style.css', LD_FILE ), array(), LD_VERSION );
    }

    public function shortcode_events_list()
    {
        ob_start();
        $events = Helpers::get_events();
        include LD_DIR.'/templates/shortcode_events_list.php';
        return ob_get_clean();
    }

    public function shortcode_export_events()
    {
        return "";
    }

    public function export_events()
    {
        if( !is_admin() ) :

            if( is_singular() && Helpers::has_export_events_shortcode( get_queried_object_id() ) ) :
                wp_send_json( Helpers::get_events( 'any' ) );
            endif;

        endif;
    }
}
