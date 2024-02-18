<?php
/**
 * Configuration Settings
 */
// Directory for storing files (e.g. 'cache/')
$storage = 'cache/';
// Time in seconds for renewing the tiles
$ttl = 604800;
// Email address of the proxy operator
$operator = 'you@mail.com';

/**
 * Read Input Variables
 */
$z = $_GET['z'];
$x = $_GET['x'];
$y = $_GET['y'];
if(empty($z) OR empty($x) OR empty($y)) {
    // If any of the variables are empty, terminate the script
    die;
}

/**
 * Function to Download Tiles
 */
function download($storage, $z, $x, $y, $operator) {
    // Set maximum execution time to unlimited
    set_time_limit(0);
    // Define the source URL for the tile
    $source = 'https://tile.openstreetmap.org/' . $z . '/' . $x . '/' . $y . '.png';
    // Set timeout for the download
    $timeout = 30;
    // Open file handler for writing
    $fh = fopen($storage . $z . '/' . $x . '/' . $y . '.png', 'w');
    // Initialize cURL session
    $ch = curl_init();
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $source);
    curl_setopt($ch, CURLOPT_FILE, $fh);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_USERAGENT, 'osm-caching-proxy, Operator: ' . $operator);
    // Execute cURL session
    curl_exec($ch);
    // Close cURL session
    curl_close($ch);
    // Close file handler
    fclose($fh);
}

// Check if directories exist and download tile
if(!file_exists($storage)) {
    mkdir($storage, 0750);
}
if(!file_exists($storage . $z)) {
    mkdir($storage . $z, 0750);
}
if(!file_exists($storage . $z . '/' . $x)) {
    mkdir($storage . $z . '/' . $x, 0750);
}
if(file_exists($storage . $z . '/' . $x . '/' . $y . '.png')) {
    // If the tile exists, check its age
    $age = filemtime($storage . $z . '/' . $x . '/' . $y . '.png');
    // If the tile is older than the defined TTL, download a new one
    if((time() + $ttl) >= $age) {
        download($storage, $z, $x, $y);
    }
}
else {
    // If the tile doesn't exist, download it
    download($storage, $z, $x, $y);
}

// Output the tile image
header('Content-Type: image/png');
readfile($storage . $z . '/' . $x . '/' . $y . '.png');