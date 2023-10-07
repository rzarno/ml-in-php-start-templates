<?php

namespace service\stage;

use Interop\Polite\Math\Matrix\NDArray;
use League\Pipeline\StageInterface;
use Rindow\Math\Matrix\MatrixOperator;
use service\model\Payload;

class ImagePreprocesor implements StageInterface
{
    public function __construct(
        private readonly MatrixOperator $matrixOperator
    ) {
    }

    public function flattenAndNormalizeImage($trainImg, $inputShape): NDArray
    {
        $dataSize = $trainImg->shape()[0];
        $trainImg = $trainImg->reshape(array_merge([$dataSize], $inputShape));
        return $this->matrixOperator->scale(1.0/255.0, $this->matrixOperator->astype($trainImg, NDArray::float32));
    }

    /**
     * @param Payload $payload
     * @return Payload
     */
    public function __invoke($payload)
    {
        echo "formating train image ...\n";
        $trainImg = $this->flattenAndNormalizeImage($payload->getTrainImg(), $payload->getConfigInputShape());
        $trainLabel = $this->matrixOperator->la()->astype($payload->getTrainLabel(), NDArray::int32);
        echo "formating test image ...\n";
        $testImg  = $this->flattenAndNormalizeImage($payload->getTestImg(), $payload->getConfigInputShape());
        $testLabel = $this->matrixOperator->la()->astype($payload->getTestLabel(), NDArray::int32);

        $payload
            ->setTrainImg(null)
            ->setTrainLabel(null)
            ->setTestImg(null)
            ->setTestLabel(null)
            ->setNormalizedTrainImg($trainImg)
            ->setNormalizedTrainLabel($trainLabel)
            ->setNormalizedTestImg($testImg)
            ->setNormalizedTestLabel($testLabel);

        return $payload;
    }
}
