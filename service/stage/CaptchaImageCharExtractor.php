<?php

namespace service\stage;

use Imagick;
use League\Pipeline\StageInterface;
use service\CaptchaCharEncoder;
use service\ImageTransform;
use service\model\CNNPayload;

class CaptchaImageCharExtractor implements StageInterface
{
    public function __construct(
        private readonly ImageTransform $imageTransform,
        private readonly CaptchaCharEncoder $captchaCharEncoder
    ) {
    }

    public function extract(array $images, int $width, int $height)
    {
        echo "extracting chars\n";
        $charsImg = [];
        $charsLabel = [];
        foreach ($images as $photo => $chars) {
            $im1 = new Imagick(__DIR__ . '/../../data/captcha/' . $photo);
            $im1->trimImage(0);
            $im1->resizeImage(240, $height, Imagick::FILTER_GAUSSIAN, 1);
            $im1->writeImage('sample.jpg');
            for ($i = 0; $i < 6; $i++) {
                $im1c = new Imagick('sample.jpg');
                $im1c->cropImage($width, $height, $i * $width, 0);
                $charsImg[] = $this->imageTransform->exportRGBArray($im1c);
                $charsLabel[] = $this->captchaCharEncoder->encode($chars[$i]);
            }
        }
        return [$charsImg, $charsLabel];
    }

    /**
     * @param CNNPayload $payload
     */
    public function __invoke($payload)
    {
        [$charsImg, $charsLabel] = $this->extract(
            $payload->getImportedData(),
            $payload->getConfigImgWidth(),
            $payload->getConfigImgHeight()
        );
        $payload->setDataX($charsImg);
        $payload->setDataY($charsLabel);
        return $payload;
    }
}
