<?php snippet('header') ?>

<h1>The Garden 𖡼.𖤣𖥧𖡼.𖤣𖥧</h1>

<?php

// Check if a file was uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Check for upload errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Move the uploaded file to the 'assets' folder
        $uploadDir = 'assets/uploads/';
        $uploadPath = $uploadDir . basename($file['name']);

        // Create the directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move the uploaded file to the assets folder
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            echo '<p>Seed planted successfully!</p>';
        } else {
            echo '<p>File upload failed.</p>';
        }
    } else {
        echo '<p>For some reason the seed could not be planted. Please try again.</p>';
    }
}

?>

<h2>Plant a Seed</h2>

<!-- File upload form -->
<form method="POST" enctype="multipart/form-data">
    <label for="file">Choose a seed to plant:</label>
    <input type="file" name="file" id="file" required>
    <br>
    <button type="submit">Plant</button>
</form>

<hr>

<h2>Planted Seeds</h2>

<?php
// List uploaded files from the 'assets/uploads' directory
$uploadDir = 'assets/uploads/';
$files = array_diff(scandir($uploadDir), ['.', '..']); // Exclude . and .. from the list

if (empty($files)) {
    echo '<p>Nothing is planted yet... :(</p>';
} else {
    echo '<ul>';
    foreach ($files as $file) {
        echo '<li><a href="' . $uploadDir . $file . '" target="_blank">' . $file . '</a></li>';
    }
    echo '</ul>';
}
?>
