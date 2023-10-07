<?php

namespace service\stage;

use Imagick;
use League\Pipeline\StageInterface;
use service\CaptchaCharEncoder;
use service\ImageTransform;
use service\model\Payload;

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
            $im1 = new Imagick(__DIR__ . '/../../image/captcha/' . $photo);
            $im1->trimImage(0);
            $im1->resizeImage(240, $height, Imagick::FILTER_GAUSSIAN, 1);
            for ($i = 0; $i < 6; $i++) {
                $im1c = clone $im1;
                $im1c->cropImage($width, $height, $i * $width, 0);
                $im1c->resizeImage($width, $height, Imagick::FILTER_GAUSSIAN, 1);
                $charsImg[] = $this->imageTransform->exportRGBArray($im1c);
                $charsLabel[] = $this->captchaCharEncoder->encode($chars[$i]);
            }
        }
        return [$charsImg, $charsLabel];
    }

    /**
     * @param Payload $payload
     */
    public function __invoke($payload)
    {
        [$charsImg, $charsLabel] = $this->extract(
            $payload->getImportedData(),
            $payload->getConfigImgWidth(),
            $payload->getConfigImgHeight()
        );
        $payload->setDataImg($charsImg);
        $payload->setDataLabel($charsLabel);
        return $payload;
    }
}
