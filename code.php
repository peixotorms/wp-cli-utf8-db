<?php

# add this function somewhere on yout theme or using a plugin
# copied from https://developer.wordpress.org/reference/functions/maybe_convert_table_to_utf8mb4/
function new_maybe_convert_table_to_utf8mb4( $table ) {
    global $wpdb;
 
    $results = $wpdb->get_results( "SHOW FULL COLUMNS FROM `$table`" );
    if ( ! $results ) {
        return false;
    }
 
    foreach ( $results as $column ) {
        if ( $column->Collation ) {
            list( $charset ) = explode( '_', $column->Collation );
            $charset         = strtolower( $charset );
            if ( 'utf8' !== $charset && 'utf8mb4' !== $charset ) {
                // Don't upgrade tables that have non-utf8 columns.
                return false;
            }
        }
    }
 
    $table_details = $wpdb->get_row( "SHOW TABLE STATUS LIKE '$table'" );
    if ( ! $table_details ) {
        return false;
    }
 
    list( $table_charset ) = explode( '_', $table_details->Collation );
    $table_charset         = strtolower( $table_charset );
    if ( 'utf8mb4' === $table_charset ) {
        return true;
    }
 
    return $wpdb->query( "ALTER TABLE $table CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci" );
}
