<?php

require __DIR__ . '/../../vendor/autoload.php';

use Imagick;

$photos = [
    1 => __DIR__ . '/../../image/captcha/resolved_captcha_13609752.jpg',
    2 => __DIR__ . '/../../image/captcha/resolved_captcha_13609750.jpg',
    3 => __DIR__ . '/../../image/captcha/resolved_captcha_13609821.jpg',
    4 => __DIR__ . '/../../image/captcha/resolved_captcha_13609822.jpg',
    5 => __DIR__ . '/../../image/captcha/resolved_captcha_13609844.jpg'
];
//w/h 224
foreach ($photos as $key => $photo) {
    $im1 = new Imagick($photo);
    $im1->writeImage("sample{$key}.jpg");
    $im1->trimImage(0);
    $im1->resizeImage(250, 50, Imagick::FILTER_GAUSSIAN, 1);

    $im1->writeImage("sample{$key}_trimed.jpg");
    for ($i = 1; $i <= 6; $i++) {
        $im1c = clone $im1;
        $im1c->cropImage(40, 50, $i * 40, 0);
        $im1c->writeImage("sample{$key}$i.jpg");
    }
}
