<?php

//Include config.php from feed folder
include '../feed/config.php';
include '../feed/build_feed_xml.php';
include '../feed/connector.php';

// Get feed items from database
$query = Feed\getFeedItems(DRAFT_ITEM_TABLE);

// Create feed
Feed\build_feed_xml($query);