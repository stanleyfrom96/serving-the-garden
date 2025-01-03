<?php
// Page content for the homepage

// Handle form submission and file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // Define allowed file types and max file size
    $allowedTypes = ['text/plain', 'image/jpeg', 'audio/mpeg'];
    $maxFileSize = 1048576; // 1MB in bytes

    $file = $_FILES['file'];
    $fileType = mime_content_type($file['tmp_name']);
    $fileSize = $file['size'];

    // Check file type and size
    if (in_array($fileType, $allowedTypes) && $fileSize <= $maxFileSize) {
        // Generate a unique name for the file
        $fileName = uniqid() . '-' . basename($file['name']);
        $uploadDir = kirby()->roots()->content() . '/uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $uploadPath = $uploadDir . $fileName;

        // Move the file to the upload directory
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // File uploaded successfully
            $message = "File uploaded successfully!";
        } else {
            $message = "Failed to upload file.";
        }
    } else {
        $message = "Invalid file type or file size exceeds 1MB.";
    }
}

// Get all uploaded files excluding system files like uploads.txt
$files = [];
if ($handle = opendir(kirby()->roots()->content() . '/uploads/')) {
    while (false !== ($entry = readdir($handle))) {
        // Exclude directories (., ..) and any specific system files like uploads.txt
        if ($entry != "." && $entry != ".." && $entry != "uploads.txt") {
            $files[] = $entry;
        }
    }
    closedir($handle);
}

?>

<?php snippet('header') ?>

<h1>The Garden 𖡼.𖤣𖥧𖡼.𖤣𖥧</h1>

<!-- File Upload Form -->
<form action="" method="POST" enctype="multipart/form-data">
    <label for="file">Choose a file:</label>
    <input type="file" name="file" id="file" accept=".txt,.jpg,.mp3" required>
    <button type="submit">Upload</button>
</form>

<!-- Upload Message -->
<?php if (isset($message)): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<div class="upload-container">
    <?php foreach ($files as $file): ?>
        <div class="file-item" style="top: <?php echo rand(0, 90); ?>%; left: <?php echo rand(0, 90); ?>%;" data-size="small">

            <?php
            $filePath = kirby()->roots()->content() . '/uploads/' . $file;
            $fileType = mime_content_type($filePath);

            // Determine category based on file type
            $category = '';
            if (strpos($fileType, 'text/') === 0) {
                $category = 'Text';
            } elseif (strpos($fileType, 'image/') === 0) {
                $category = 'Image';
            } elseif (strpos($fileType, 'audio/') === 0) {
                $category = 'Audio';
            }
            ?>

            <?php if ($category === 'Image'): ?>
                <img src="<?php echo url('content/uploads/' . $file); ?>" alt="Image">
            <?php elseif ($category === 'Audio'): ?>
                <audio controls>
                    <source src="<?php echo url('content/uploads/' . $file); ?>" type="<?php echo $fileType; ?>">
                    Your browser does not support the audio element.
                </audio>
            <?php elseif ($category === 'Text'): ?>
                <pre><?php echo htmlspecialchars(file_get_contents($filePath)); ?></pre>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>
</div>

<?php snippet('footer') ?>