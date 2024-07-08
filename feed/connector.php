<?php

namespace Feed;

// Prevent direct access to this file
if (!defined('FEED_ITEM_TABLE')) {
    die('Direct access not permitted');
}

function getFeedItems($table, $show_messages = true)
{
    // Create connection
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    // Check connection
    if (mysqli_connect_errno($con && $show_messages)) {
        echo '<p class = "message error">Database connection failed!: ' . mysqli_connect_error() . '</p>';
        exit();
    }

    // Set connection to UTF-8 ---- SUPER IMPORTANT!!! SAVE YOURSELF A LOT OF HEADACHES
    mysqli_set_charset($con, 'utf8');

    $sql = '';
    $sql .= 'SELECT * FROM ' . $table . ' ORDER BY id ASC';

    if (FEED_ITEM_LIMIT >= 0) {
        $sql .= ' LIMIT ' . FEED_ITEM_LIMIT;
    }


    $query = mysqli_query($con, $sql);

    if (!$query && $show_messages) {
        echo '<p class = "message error">Database query failed!: ' . mysqli_error($con) . '</p>';
        exit();
    }

    // Close connection
    mysqli_close($con);

    return $query;
};




// Write feed items to database table
function writeFeedItems($items, $table)
{
    // Create connection
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    // Check connection
    if (mysqli_connect_errno($con)) {
        echo '<p class = "message error">Database connection failed!: ' . mysqli_connect_error() . '</p>';
        exit();
    }

    // Set connection to UTF-8 ---- SUPER IMPORTANT!!! SAVE YOURSELF A LOT OF HEADACHES
    mysqli_set_charset($con, 'utf8');

    // If the table does not exist, create it with "create table draft_items like feed_items";
    $sql = create_table_structure_query($table);
    $query = mysqli_query($con, $sql);

    // Delete all rows from table
    $sql = 'DELETE FROM ' . $table;
    $query = mysqli_query($con, $sql);

    if (!$query) {
        echo '<p class = "message error">Database query failed!: ' . mysqli_error($con) . '</p>';
        exit();
    }


    // Loop through the items array
    foreach ($items as $key => $item) {
        // Escape the values

        // Use array key as id (counting from 1 not 0)
        $id = $key + 1;

        $pub_date = mysqli_real_escape_string($con, $item['pub_date']);
        $guid = mysqli_real_escape_string($con, $item['guid']);
        $title = mysqli_real_escape_string($con, $item['title']);
        $link = mysqli_real_escape_string($con, $item['link']);
        $media_url = mysqli_real_escape_string($con, $item['media_url']);
        $description = mysqli_real_escape_string($con, $item['description']);

        // Insert the values into the table
        $sql = '';
        $sql .= 'INSERT INTO ' . $table . ' (';
        $sql .= 'id, pub_date, guid, title, link, media_url, description';
        $sql .= ') VALUES (';
        $sql .= '"' . $id . '", ';
        $sql .= '"' . $pub_date . '", ';
        $sql .= '"' . $guid . '", ';
        $sql .= '"' . $title . '", ';
        $sql .= '"' . $link . '", ';
        $sql .= '"' . $media_url . '", ';
        $sql .= '"' . $description . '"';
        $sql .= ')';

        $query = mysqli_query($con, $sql);

        if (!$query) {
            echo '<p class = "message error">Database query failed!: ' . mysqli_error($con) . '</p>';
            exit();
        }
    }

    // if successful, echo success message
    echo '<p class = "message">Feed items successfully saved as draft!</p>';

    // Close connection
    mysqli_close($con);
};



function publishDraftItems()
{
    // Create connection
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    // Check connection
    if (mysqli_connect_errno($con)) {
        echo '<p class = "message error">Database connection failed!: ' . mysqli_connect_error() . '</p>';
        exit();
    }

    // Set connection to UTF-8 ---- SUPER IMPORTANT!!! SAVE YOURSELF A LOT OF HEADACHES
    mysqli_set_charset($con, 'utf8');

    // Does draft item table exist? show tables with exact name
    $sql = 'SHOW TABLES LIKE "' . DRAFT_ITEM_TABLE . '"';
    $query = mysqli_query($con, $sql);

    if (mysqli_num_rows($query) == 0) {
        echo '<p class = "message">Upload CSV file to get started.</p>';
        exit();
    }

    // If the table does not exist, create it with create table from create_table_structure_query function.
    $sql = create_table_structure_query(FEED_ITEM_TABLE);
    $query = mysqli_query($con, $sql);

    // Replace the published table with the draft table
    $sql = '';
    $sql .= 'RENAME TABLE ';
    $sql .= FEED_ITEM_TABLE . ' TO ' . FEED_ITEM_TABLE . '_old, ';
    $sql .= DRAFT_ITEM_TABLE . ' TO ' . FEED_ITEM_TABLE;
    $query = mysqli_query($con, $sql);

    $sql = '';
    // If table exists, drop it
    $sql .= 'DROP TABLE IF EXISTS ' . FEED_ITEM_TABLE . '_old';
    $query = mysqli_query($con, $sql);

    if (!$query) {
        echo '<p class = "message error">Database query failed!: ' . mysqli_error($con) . '</p>';
        exit();
    }

    echo '<p class = "message">Published to live feed.</p>';
    // Check feed here
    echo '<a class = "btn-lg btn-blue" href="' . RSS_LINK . '" target="_blank">Check Live Feed</a>';

    // Hide publish button, preview button and upload button
    echo '<script>
    document.getElementById("publish").style.display = "none";
    document.getElementById("preview").style.display = "none";
    document.getElementById("upload").style.display = "none";
    </script>';

    // Close connection
    mysqli_close($con);
}
