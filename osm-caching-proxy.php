<?php
// Configuration
// Directory for files storage (e.g. 'cache/')
$storage = 'cache/';
// Time for renewing the tiles in seconds
$ttl = 604800;
// Mail address of proxy operator
$operator = 'you@mail.com';

// Read variables
$z = $_GET['z'];
$x = $_GET['x'];
$y = $_GET['y'];
if(empty($z) OR empty($x) OR empty($y)) {

    die;

}


/**
 * Function to download the tiles
 */
function download($storage, $z, $x, $y) {

    set_time_limit(0);
    $source = 'https://tile.openstreetmap.org/' . $z . '/' . $x . '/' . $y . '.png';
    $timeout = 30;

    $fh = fopen($storage . $z . '/' . $x . '/' . $y . '.png', 'w');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $source);
    curl_setopt($ch, CURLOPT_FILE, $fh);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_USERAGENT, 'osm-caching-proxy, Operator: ' . $operator);
    curl_exec($ch);
    curl_close($ch);
    fclose($fh);

}

// Check, if directories exist & download tile
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
    $age = filemtime($storage . $z . '/' . $x . '/' . $y . '.png');
    if((time() + $ttl) >= $age) {

        download($storage, $z, $x, $y);

    }
}
else {

    download($storage, $z, $x, $y);

}

// Get out tile image
header('Content-Type: image/png');
readfile($storage . $z . '/' . $x . '/' . $y . '.png');