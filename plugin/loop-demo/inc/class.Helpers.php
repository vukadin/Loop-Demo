<?php
namespace Loop;

class Helpers 
{
    public static function get_event_edit_nonce( $post_id )
    {
        return sprintf(
            'edit_event_%d',
            $post_id
        );
    }

    public static function get_event_post_id( $event_id )
    {
        $posts = get_posts(
            array(
                'post_type' => 'event',
                'post_status' => array( 'any', 'trash' ),
                'posts_per_page' => 1,
                'meta_key' => '_event_id',
                'meta_value' => $event_id
            )
        );

        return !empty( $posts ) ? $posts[0]->ID : false;
    }

    public static function create_event_post( $event )
    {
        $post_id = wp_insert_post(
            array(
                'post_title' => $event['title'],
                'post_type' => 'event',
                'post_status' => $event['isActive'] ? 'publish' : 'draft'
            )
        );

        self::update_event_post_meta( $post_id, $event );
        self::update_event_post_tags( $post_id, $event['tags'] );

        return $post_id;
    }

    public static function update_event_post( $post_id, $event )
    {
        wp_update_post(
            array(
                'ID' => $post_id,
                'post_title' => $event['title'],
                'post_type' => 'event',
                'post_status' => $event['isActive'] ? 'publish' : 'draft'
            )
        );

        self::update_event_post_meta( $post_id, $event );
        self::update_event_post_tags( $post_id, $event['tags'] );

        return $post_id;
    }

    public static function update_event_post_meta( $post_id, $event )
    {
        $meta_data = array(
            '_event_id' => $event['id'],
            '_event_organizer' => $event['organizer'],
            '_event_timestamp' => $event['timestamp'],
            '_event_email' => $event['email'],
            '_event_latitude' => $event['latitude'],
            '_event_longitude' => $event['longitude'],
            '_event_address' => $event['address'],
            '_event_about' => $event['about'],
        );

        foreach( $meta_data as $meta_key => $meta_value ) :
            update_post_meta( $post_id, $meta_key, $meta_value );
        endforeach;
    }

    public static function update_event_post_tags( $post_id, $tags )
    {
        $event_tags = array();

        foreach( $tags as $tag ) :

            $event_tag_term = get_term_by( 'name', $tag, 'event_tag' );

            if( !$event_tag_term ) :

                $event_tag_term_create = wp_insert_term( $tag, 'event_tag' );

                if( !is_wp_error( $event_tag_term_create ) ) :
                    $event_tags[] = $event_tag_term_create['term_id'];
                else :
                    print_r( $event_tag_term_create );
                    continue;
                endif;

            else :

                $event_tags[] = $event_tag_term->term_id;

            endif;

        endforeach;

        wp_set_post_terms( $post_id, $event_tags, 'event_tag' );
    }

    public static function send_import_events_notification( $message )
    {
        wp_mail( "logging@agentur-loop.com", "Event Import Report", $message );
        if( class_exists( "\WP_CLI" ) ) :
            \WP_CLI::success( $message );
        endif;
    }

    public static function get_events( $status = 'publish' )
    {
        $events = array();

        $event_posts = get_posts(
            array(
                'post_type' => 'event',
                'posts_per_page' => -1,
                'order' => 'desc',
                'orderby' => 'meta_value',
                'meta_key' => '_event_timestamp',
                'post_status' => $status
            )
        );

        foreach( $event_posts as $event_post ) :
            $events[] = self::get_event_data( $event_post );
        endforeach;

        return $events;
    }

    public static function get_event_data( $post )
    {
        return array(
            'id' => get_post_meta( $post->ID, '_event_id', true ),
            'title' => $post->post_title,
            'about' => get_post_meta( $post->ID, '_event_about', true ),
            'organizer' => get_post_meta( $post->ID, '_event_organizer', true ),
            'timestamp' => get_post_meta( $post->ID, '_event_timestamp', true ),
            'isActive' => $post->post_status === 'publish' ? 1 : 0,
            'email' => get_post_meta( $post->ID, '_event_email', true ),
            'address' => get_post_meta( $post->ID, '_event_address', true ),
            'latitude' => get_post_meta( $post->ID, '_event_latitude', true ),
            'longitude' => get_post_meta( $post->ID, '_event_longitude', true ),
            'tags' => wp_get_object_terms( $post->ID, 'event_tag', array( 'fields' => 'names' ) )
        );
    }

    public static function has_export_events_shortcode( $post_id )
    {
        global $wpdb;

        $post_content = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT post_content from {$wpdb->posts} WHERE ID = %d",
                $post_id
            )
        );

        return strpos( $post_content, '[export_events]' ) !== false;
    }
}