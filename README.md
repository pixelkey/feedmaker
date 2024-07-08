# feedmaker
 This small web app converts a CSV file to XML RSS format and stores the items in an SQL database. 
 It generates and serves an RSS feed at the hosted location.



Initial Setup

Database structure can be found in the config file.

DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci


To get started, create database and update config file with settings.

The main feed can be found at the root of the hosted address or folder. For example, feed.example.com
The draft feed can be found in the folder "draft". For example, feed.example.com/draft

To publish a feed, navigate to the draft folder. (Example, feed.example.com/draft).
Upload CSV file according to requirements.
Preview the draft xml file generated.
If you're happy with it, go ahead and publish.