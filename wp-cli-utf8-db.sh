#!/usr/bin/env bash
# Purpose - Convert all tables to UTF8MB4 with WP-CLI
# https://developer.wordpress.org/reference/functions/maybe_convert_table_to_utf8mb4/

# Create array of all tables
WPTABLES=($(wp db tables --all-tables))

# loop through array and alter tables
for WPTABLE in ${WPTABLES[@]}
do
    echo "Converting ${WPTABLE} to UTF8MB4"
    wp eval "echo maybe_convert_table_to_utf8mb4( ${WPTABLE} );" --allow-root
    echo "Converted ${WPTABLE} to UTF8MB4"
done
