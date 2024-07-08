<?php

// A html form to upload a CSV file to the database
// The form is submitted to itself
// The form is processed by the upload.php script
// The upload.php script is in the draft folder

// Include config.php from feed folder
include '../feed/config.php';
include '../feed/connector.php';

// Set header to html
header('Content-Type: text/html; charset=utf-8');

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Upload CSV</title>

    <!-- style sheet -->
    <link rel="stylesheet" href="../public/reset.css">
    <link rel="stylesheet" href="../public/style.css">

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Convert CSV to RSS Feed</h1>
                <ol>
                    <li>CSV requires headers: pub_date, guid, title, link, media_url, description</li>
                    <li>Please ensure the CSV file is UTF-8 encoded.</li>
                    <li>Select and upload your CSV file containing new feed items.</li>
                    <li>Draft feed is now saved and can be published now or later.</li>
                    <li>Preview the generated draft feed XML file.</li>
                    <li>Publish the draft feed to the live feed.</li>
                    <li>The live feed items will be replaced with the new feed items.</li>
                    <li>View the live feed XML file.</li>
                </ol>

                <!-- HTML form -->
                <div class="form-container" id="upload">
                    <form method="post" enctype="multipart/form-data">
                        <label for="file">Select CSV File</label>
                        <input type="file" name="file" id="file">
                        <input class="btn-lg btn-blue" type="submit" name="upload" value="Upload and Save as Draft">
                    </form>
                </div>

                <?php
                // If the form is submitted, process the file
                if (isset($_POST['upload'])) {
                    // Check if the file is a CSV file
                    if ($_FILES['file']['type'] == 'text/csv') {
                        // If the file is a CSV file, open it
                        if (($handle = fopen($_FILES['file']['tmp_name'], 'r')) !== false) {

                            // Create an array to hold the CSV data
                            $csv_data = array();
                            $id = 0;

                            // Loop through the CSV file
                            while (($data = fgetcsv($handle, null, ',')) !== false) {

                                // Use first row as keys
                                if (empty($keys)) {
                                    $keys = $data;
                                    // Remove all non-utf-8 characters from keys
                                    $keys = preg_replace('/[^(\x20-\x7F)]*/', '', $keys);
                                    $keys = array_map('utf8_encode', $keys);
                                    continue;
                                }

                                [$pub_date, $guid, $title, $link, $media_url, $description] = $data;
                                // Create an array of the CSV data
                                $items[] = array_combine($keys, $data);
                            }

                            Feed\writeFeedItems($items, DRAFT_ITEM_TABLE);

                            // Close the file
                            fclose($handle);
                        }
                    } else {
                        // If the file is not a CSV file, display an error message
                        echo '<p class="message error">Please select and upload a CSV file.</p>';
                    }

                    // If file then unlink
                    if (file_exists($_FILES['file']['tmp_name'])) {
                        unlink($_FILES['file']['tmp_name']);
                    }
                }

                // chech if draft table is populated. If so then show button to publish
                if (Feed\getFeedItems(DRAFT_ITEM_TABLE, false) != null) {
                    // Button to preview the draft items. Open in new window
                    echo '<a class="btn-lg btn-blue" id="preview" href="preview.php" target="_blank">Preview Draft Feed</a>';
                ?>
                    <div class="form-container" id="publish">
                        <!-- Form button to publish the draft items -->
                        <form method="post">
                            <input class="btn-lg btn-red" type="submit" name="publish" value="Publish Draft to Live Feed">
                        </form>
                    </div>
                <?php
                }

                // If the form is submitted, process the file
                if (isset($_POST['publish'])) {
                    // Publish the draft items
                    Feed\publishDraftItems();
                }
                ?>

            </div>
        </div>
    </div>
</body>

</html>