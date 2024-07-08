<?php

// Get server path to this file and set it as the root path
$root_path = dirname(__FILE__);

include $root_path . '/feed/config.php';
include $root_path . '/feed/build_feed_xml.php';
include $root_path . '/feed/connector.php';

// Get feed items from database
$query = Feed\getFeedItems(FEED_ITEM_TABLE);

// Create feed
Feed\build_feed_xml($query);