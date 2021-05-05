<div class="events" >
    <?php foreach( $events as $event ) : 
        
        $timestamp = strtotime( $event['timestamp'] );
        $time_difference = human_time_diff( $timestamp );

        if( $timestamp > time() ) :
           $time = sprintf( 
               __( 'Starts in %s', 'loop' ),
               $time_difference
           ); 
        else :
            $time = sprintf( 
                __( 'Ended %s ago', 'loop' ),
                $time_difference
            ); 
        endif;   
    ?>
    <div class="event" >
        <div class="event-title" ><?php echo $event['title']; ?></div>
        <div class="event-description" >
            <div class="event-organizer" ><strong><?php _e( "Time", "loop" ); ?>:</strong> <?php echo $time; ?></div>
            <div class="event-organizer" ><strong><?php _e( "Organizer", "loop" ); ?>:</strong> <?php echo $event['organizer']; ?></div>
            <div class="event-location" ><strong><?php _e( "Address", "loop" ); ?>:</strong> <?php echo $event['address']; ?></div>
            <div class="event-location" ><strong><?php _e( "Tags", "loop" ); ?>:</strong> <?php echo join(', ', $event['tags'] ); ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>