<?php // ==== FEED ==== //

// Standardize image sizes on feeds; just send medium regardless of what's in the original content
function ubik_feed_images( $size ) {
    if ( is_feed() )
        return 'medium';
    return $size;
}
add_filter( 'post_thumbnail_size', 'ubik_feed_images' );
add_filter( 'ubik_image_markup_size', 'ubik_feed_images' );



// Delay feed update
function publish_later_on_feed($where) {
    global $wpdb;

    if (is_feed()) {
        // timestamp in WP-format
        $now = gmdate('Y-m-d H:i:s');

        // value for wait; + device
        $wait = '10'; // integer

        // http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function_timestampdiff
        $device = 'MINUTE'; // MINUTE, HOUR, DAY, WEEK, MONTH, YEAR

        // add SQL-sytax to default $where
        $where .= " AND TIMESTAMPDIFF($device, $wpdb->posts.post_date_gmt, '$now') > $wait ";
    }
    return $where;
}
// Untested
//add_filter('posts_where', 'publish_later_on_feed');
