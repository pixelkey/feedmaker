<?php

namespace Feed;


/** MySQL database name */
define('DB_NAME', 'local');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** RSS Feed Table */
define('FEED_ITEM_TABLE', 'feed_items');

/** RSS Draft Feed Table */
define('DRAFT_ITEM_TABLE', 'draft_items');

/** Number of rows */
define('FEED_ITEM_LIMIT', -1);


/** RSS Title */
define('RSS_TITLE', 'Example Feed');

/** RSS Description */
define('RSS_DESCRIPTION', 'The feed description');

/** RSS Link */
define('RSS_LINK', 'https://feed.example.com');

/** RSS Language */
define('RSS_LANGUAGE', 'en-au');


/** Define SQL table structure query to make table
create table if not exists {{TABLE NAME}}
(
    id          INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    guid        INT           null,
    pub_date    varchar(256)  null,
    title       text          null,
    link        text          null,
    media_url   text          null,
    description text          null
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
*/

function create_table_structure_query($table)
{
    $sql = '';
    $sql .= 'CREATE TABLE IF NOT EXISTS ' . $table . ' (';
    $sql .= 'id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,';
    $sql .= 'guid INT NULL,';
    $sql .= 'pub_date VARCHAR(256) NULL,';
    $sql .= 'title TEXT NULL,';
    $sql .= 'link TEXT NULL,';
    $sql .= 'media_url TEXT NULL,';
    $sql .= 'description TEXT NULL';
    $sql .= ') DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;';

    return $sql;
}
