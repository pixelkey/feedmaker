<?php

namespace Feed;

// Prevent direct access to this file
if (!defined('FEED_ITEM_TABLE')) {
    die('Direct access not permitted');
}

function build_feed_xml($query)
{

    // The RSS feed is an XML content type
    header('Content-type: text/xml');

    $header = '';
    $items = '';
    $footer = '';

    // Build header
    $header .= '<?xml version="1.0" encoding="UTF-8"?>';
    $header .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
    $header .= '<channel>';
    $header .= '<title>' . RSS_TITLE . '</title>';
    $header .= '<link>' . RSS_LINK . '</link>';
    $header .= '<atom:link href="' . RSS_LINK . '" rel="self" type="application/rss+xml" />';
    $header .= '<description>' . RSS_DESCRIPTION . '</description>';
    $header .= '<language>' . RSS_LANGUAGE . '</language>';

    // Loop through feed items
    while ($row = mysqli_fetch_array($query)) {
        $item = '';
        $guid = $row['guid'];
        $pubDate = $row['pub_date'];
        $title = $row['title'];
        $link = $row['link'];
        $description = $row['description'];
        $media_url = $row['media_url'];

        // Build item
        $item .= '<item>';
        $item .= '<pubDate>' . $pubDate . '</pubDate>';
        $item .= '<title>' . $title . '</title>';
        $item .= '<link>' . $link . '?guid=' . $guid . '</link>';
        $item .= '<description><![CDATA[' . $description . '<br><a href = "' . $media_url . '" target="_blank">Watch Video - ' . $title . '</a>]]></description>';
        $item .= '<guid isPermaLink="false">id' . $guid . '</guid>';
        $item .= '</item>';

        // Add item to items
        $items .= $item;
    }

    // Build footer
    $footer .= '</channel></rss>';


    // Complete RSS Feed
    $feed = $header . $items . $footer;
    echo $feed;
}
