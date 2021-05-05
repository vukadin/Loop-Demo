<table class="form-table" >
    <tr>
        <th>
            <label for="event-organizer" ><?php _e( "Organizer", "loop" ); ?></label>
        </th>
        <td>
            <input type="text" class="regular-text"  name="event[organizer]" id="event-organizer" value="<?php echo esc_attr( $organizer ); ?>" />
        </td>
    </tr>
    <tr>
        <th>
            <label for="event-timestamp" ><?php _e( "Time", "loop" ); ?></label>
        </th>
        <td>
            <input type="text" class="regular-text"  name="event[timestamp]" id="event-timestamp" value="<?php echo esc_attr( $timestamp ); ?>" />
        </td>
    </tr>
    <tr>
        <th>
            <label for="event-email" ><?php _e( "Email", "loop" ); ?></label>
        </th>
        <td>
            <input type="email" class="regular-text"  name="event[email]" id="event-email" value="<?php echo esc_attr( $email ); ?>" />
        </td>
    </tr>
    <tr>
        <th>
            <label for="event-latitude" ><?php _e( "Latitude", "loop" ); ?></label>
        </th>
        <td>
            <input type="text" class="regular-text"  name="event[latitude]" id="event-latitude" value="<?php echo esc_attr( $latitude ); ?>" />
        </td>
    </tr>
    <tr>
        <th>
            <label for="event-longitude" ><?php _e( "Longitude", "loop" ); ?></label>
        </th>
        <td>
            <input type="text" class="regular-text"  name="event[longitude]" id="event-longitude" value="<?php echo esc_attr( $longitude ); ?>" />
        </td>
    </tr>
    <tr>
        <th>
            <label for="event-address" ><?php _e( "Address", "loop" ); ?></label>
        </th>
        <td>
            <input type="text" class="large-text"  name="event[address]" id="event-address" value="<?php echo esc_attr( $address ); ?>" />
        </td>
    </tr>
    <tr>
        <th>
            <label for="event-about" ><?php _e( "About", "loop" ); ?></label>
        </th>
        <td>
            <textarea name="event[about]" id="event-about" class="large-text" rows="5"><?php echo esc_textarea( $about ); ?></textarea>
        </td>
    </tr>
</table>
<?php wp_nonce_field( $nonce, '_event_nonce', false ); ?>