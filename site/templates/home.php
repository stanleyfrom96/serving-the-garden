<?php
// Page content for the homepage

// Handle form submission and file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // Define allowed file types and max file size
    $allowedTypes = ['text/plain', 'image/jpeg', 'audio/mpeg'];
    $maxFileSize = 1048576; // 1MB in bytes

    // Get the file info
    $file = $_FILES['file'];
    $fileType = mime_content_type($file['tmp_name']);
    $fileSize = $file['size'];
    
    // Get user-provided file name (without extension)
    $userFileName = isset($_POST['filename']) ? $_POST['filename'] : pathinfo($file['name'], PATHINFO_FILENAME);
    $userFileName = preg_replace("/[^a-zA-Z0-9\-_]/", "", $userFileName); // Sanitizing the file name (optional)

    // Get the file extension
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

    // Check file type and size
    if (in_array($fileType, $allowedTypes) && $fileSize <= $maxFileSize) {
        // Define upload directory
        $uploadDir = kirby()->roots()->content() . '/uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Construct the full file name
        $finalFileName = $userFileName . '.' . $fileExtension;
        $uploadPath = $uploadDir . $finalFileName;

        // Check if the file already exists and make it unique
        $originalFileName = $finalFileName;
        $counter = 1;

        while (file_exists($uploadPath)) {
            // If the file exists, append a suffix (e.g., file-1, file-2)
            $fileInfo = pathinfo($originalFileName);
            $uploadPath = $uploadDir . $fileInfo['filename'] . '-' . $counter . '.' . $fileInfo['extension'];
            $counter++;
        }

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
    <label for="filename">File Name:</label>
    <input type="text" name="filename" id="filename" placeholder="Enter a file name" required>
    <br><br>

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
            $originalFileName = pathinfo($file, PATHINFO_BASENAME);
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

            <p class="file-name">
                <?php echo htmlspecialchars($originalFileName); ?>
            </p> 
            <p class="file-type">
                (<?php echo $category; ?>)
            </p>
            
            <?php 
            // Display the file depending on its type
            if ($category === 'Image'): ?>
                <img class="file-image" src="<?php echo url('content/uploads/' . $file); ?>" alt="Image">
            <?php elseif ($category === 'Audio'): ?>
                <audio class="file-audio"  controls>
                    <source src="<?php echo url('content/uploads/' . $file); ?>" type="<?php echo $fileType; ?>">
                    Your browser does not support the audio element.
                </audio>
            <?php elseif ($category === 'Text'): ?>
                <pre class="file-text" ><?php echo htmlspecialchars(file_get_contents($filePath)); ?></pre>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php snippet('footer') ?>
