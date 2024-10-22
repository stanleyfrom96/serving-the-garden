<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>designdepartment</title>
    <link rel="stylesheet" href="./style.css" />
    <link rel="icon" href="./favicon.ico" type="image/x-icon" />
  </head>
  <body>
    <main>
      <h1>> designdepartment</h1>
      <h3>guidelines</h3>
      <h3>/images</h3>
      <?php
        // Function to list files and directories recursively
        function listDirectory($dir) {
            if ($path = opendir($dir)) {
                echo "<ul>";                
                while (false !== ($entry = readdir($path))) {
                    if ($entry != "." && $entry != "..") {
                        $fullPath = "$dir/$entry";
                        
                        // Check if the entry is a directory
                        if (is_dir($fullPath)) {
                            echo "<li><strong>/$entry</strong></li>";
                            // Recursively list the subdirectory
                            listDirectory($fullPath);
                        } else {
                            // It's a file, create a download/view link
                            echo "<li><a href='$fullPath' target='_blank'>".htmlspecialchars($entry)."</a></li>";
                        }
                    }
                }
                echo "</ul>";
                closedir($path);
            }
        }

        // List the files and directories in the ./uploads/ directory
        listDirectory('./images/');
      ?>
      <form action="upload.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
      </form>
    </main>
    <script src="index.js"></script>
  </body>
</html>
