<?php
// Generate PadiGuard AI PWA icons

// ── 192x192 ──
$img = imagecreatetruecolor(192, 192);
$bg       = imagecolorallocate($img, 26, 71, 49);     // forest-900
$accent   = imagecolorallocate($img, 42, 157, 92);    // forest-500
$white    = imagecolorallocate($img, 255, 255, 255);
$round    = imagecolorallocate($img, 30, 125, 72);

// Background
imagefilledrectangle($img, 0, 0, 191, 191, $bg);

// Rounded corners simulation (8 circles in corners)
$r = 28;
imagefilledellipse($img, $r, $r, $r*2, $r*2, $bg);
imagefilledellipse($img, 192-$r, $r, $r*2, $r*2, $bg);
imagefilledellipse($img, $r, 192-$r, $r*2, $r*2, $bg);
imagefilledellipse($img, 192-$r, 192-$r, $r*2, $r*2, $bg);
imagefilledrectangle($img, $r, 0, 192-$r, 191, $bg);
imagefilledrectangle($img, 0, $r, 191, 192-$r, $bg);

// Fill round square with forest color again
imagefilledrectangle($img, 0, 0, 191, 191, $bg);

// Leaf icon - stem
imagesetthickness($img, 4);
imageline($img, 96, 155, 96, 105, $white);

// Leaf body using arc/polygon
$leaf = [
    96, 45,
    135, 75,
    140, 110,
    120, 130,
    96,  138,
    72,  130,
    52,  110,
    57,   75,
];
imagefilledpolygon($img, $leaf, count($leaf)/2, $accent);

// Leaf vein
imagesetthickness($img, 2);
imageline($img, 96, 50, 96, 135, $white);
imageline($img, 96, 80,  115, 100, $white);
imageline($img, 96, 80,  77,  100, $white);
imageline($img, 96, 100, 110, 115, $white);
imageline($img, 96, 100, 82,  115, $white);

imagepng($img, __DIR__ . '/public/icons/icon-192.png');
imagedestroy($img);
echo "icon-192.png created\n";

// ── 512x512 ──
$img2   = imagecreatetruecolor(512, 512);
$bg2    = imagecolorallocate($img2, 26, 71, 49);
$accent2= imagecolorallocate($img2, 42, 157, 92);
$white2 = imagecolorallocate($img2, 255, 255, 255);

imagefilledrectangle($img2, 0, 0, 511, 511, $bg2);

$leaf2 = [
    256, 120,
    360, 200,
    375, 295,
    320, 350,
    256, 368,
    192, 350,
    137, 295,
    152, 200,
];
imagefilledpolygon($img2, $leaf2, count($leaf2)/2, $accent2);

imagesetthickness($img2, 5);
imageline($img2, 256, 130, 256, 365, $white2);
imageline($img2, 256, 210, 310, 265, $white2);
imageline($img2, 256, 210, 202, 265, $white2);
imageline($img2, 256, 270, 300, 310, $white2);
imageline($img2, 256, 270, 212, 310, $white2);
imageline($img2, 256, 370, 256, 415, $white2);

imagepng($img2, __DIR__ . '/public/icons/icon-512.png');
imagedestroy($img2);
echo "icon-512.png created\n";
echo "Done.\n";
