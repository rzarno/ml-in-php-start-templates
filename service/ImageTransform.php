<?php

namespace service;

use Imagick;

class ImageTransform
{
    public function exportRGBArray(Imagick $im): array
    {
        $pixels = [
            $im->exportImagePixels(0, 0, $im->getImageWidth(), $im->getImageHeight(), "R", \Imagick::PIXEL_CHAR),
            $im->exportImagePixels(0, 0, $im->getImageWidth(), $im->getImageHeight(), "G", \Imagick::PIXEL_CHAR),
            $im->exportImagePixels(0, 0, $im->getImageWidth(), $im->getImageHeight(), "B", \Imagick::PIXEL_CHAR),
        ];
        return $pixels;
    }

    public function modifyImageRandomly(Imagick $im): Imagick
    {
        $im = clone $im;
        if (rand(1, 2) == 2) {
            $im->brightnessContrastImage(10, 10);
        } else {
            $im->brightnessContrastImage(5, 20);
        }
        if (rand(1, 2) == 2) {
            $im->flopImage();
        }
        return $im;
    }
}
