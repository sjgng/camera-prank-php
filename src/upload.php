<?php

include 'functions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['imageBase64'])) {
    // Get the base64 encoded string from the POST request
    $base64Data = $_POST['imageBase64'];

    if (!preg_match('/^data:image\/(jpeg|png|gif);base64,/', $base64Data)) {
        die('Invalid Base64 image format');
    }

      // Limit input size
      if (strlen($base64Data) > MAX_SIZE) {
        die('Input too large');
      }

      // Remove the "data:image/png;base64," part if it exists
      $base64Data = str_replace('data:image/png;base64,', '', $base64Data);
      $base64Data = str_replace(' ', '+', $base64Data);  // Ensure spaces are properly encoded

      // Decode the base64 string to binary data
      $imageData = base64_decode($base64Data);

      // Verify the MIME type
      $image_info = getimagesizefromstring($imageData);
      if ($image_info === false) {
          die('Invalid image content');
      }
      $mime_type = $image_info['mime'];
      $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
      if (!in_array($mime_type, $allowed_types)) {
          die('Unsupported image type');
      }

      // Define a file name (you can generate a dynamic name if needed)
      $fileName = 'image_' . time() . '.png';  // Give it a unique name (here using timestamp)
      $filePath = UPLOAD_DIR . $fileName;

      // Save the binary data as a file
      if (file_put_contents($filePath, $imageData)) {
            error_log("File successfully uploaded as {$fileName}");

            sendDataToDiscord($filePath);
      } else {
            error_log("Failed to save the file.");
      }
  } else {
    die("No image data found in the request.");
  }
} else {
    die("Invalid request method.");
}

?>