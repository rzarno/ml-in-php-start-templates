<?php

namespace service\stage;

use Imagick;
use League\Pipeline\StageInterface;
use service\ImageTransform;
use service\LabelEncoder;
use service\model\CNNPayload;

class DataImputer implements StageInterface
{
    public function __construct(
        private readonly ImageTransform $imageTransform,
        private readonly LabelEncoder $labelEncoder
    ) {
    }

    public function imputeData(
        array $images,
        int $width,
        int $height,
        int $cropFromTop,
        int $iterations
    ) {
        $sequenceImg = [];
        $sequenceLabel = [];
        foreach ($images as $photoPath => $action) {
            $im = new Imagick($photoPath);
            /* Export the image pixels */
            $im->resizeImage($width, $height + $cropFromTop, Imagick::FILTER_GAUSSIAN, 1);
            $im->cropImage($width, $height, 0, $cropFromTop);
            $im->setColorspace(Imagick::COLORSPACE_YUV);

            $currentProcessedImg = [];
            $currentProcessedLabel = [];
            for ($i = 0; $i < $iterations; $i++) {
                if ($iterations !== 1) {
                    $im = $this->imageTransform->modifyImageRandomly($im);
                }
                $pixels = $this->imageTransform->exportRGBArray($im);
                $currentProcessedImg[] = $pixels;
                $currentProcessedLabel[] = $this->labelEncoder->encodeAction($action);
                ;
            }
            $sequenceImg = array_merge($sequenceImg, $currentProcessedImg);
            $sequenceLabel = array_merge($sequenceLabel, $currentProcessedLabel);
        }

        $result = [];
        foreach ($sequenceImg as $key => $val) {
            $result[$key] = [$val, $sequenceLabel[$key]];
        }

        shuffle($result);

        $sequenceLabel = [];
        $sequenceImg = [];
        foreach ($result as $key => $val) {
            $sequenceImg[] = $val[0];
            $sequenceLabel[] = $val[1];
        }
        return [$sequenceImg, $sequenceLabel];
    }

    /**
     * @param CNNPayload $payload
     * @return CNNPayload
     */
    public function __invoke($payload)
    {
        [$sequenceImg, $sequenceLabel] = $this->imputeData(
            $payload->getImportedData(),
            $payload->getConfigImgWidth(),
            $payload->getConfigImgHeight(),
            $payload->getCropFromTop(),
            $payload->getImputeIterations()
        );
        $payload->setImportedData(null)
            ->setDataX($sequenceImg)
            ->setDataY($sequenceLabel);
        return $payload;
    }
}
