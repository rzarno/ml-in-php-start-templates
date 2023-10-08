<?php

namespace service\stage;

use Interop\Polite\Math\Matrix\NDArray;
use League\Pipeline\StageInterface;
use Rindow\Math\Matrix\MatrixOperator;
use service\model\CNNPayload;

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
     * @param CNNPayload $payload
     * @return CNNPayload
     */
    public function __invoke($payload)
    {
        echo "formating train image ...\n";
        $trainImg = $this->flattenAndNormalizeImage($payload->getTrainX(), $payload->getConfigInputShape());
        $trainLabel = $this->matrixOperator->la()->astype($payload->getTrainY(), NDArray::int32);
        echo "formating test image ...\n";
        $testImg  = $this->flattenAndNormalizeImage($payload->getTestX(), $payload->getConfigInputShape());
        $testLabel = $this->matrixOperator->la()->astype($payload->getTestY(), NDArray::int32);

        $payload
            ->setTrainX(null)
            ->setTrainY(null)
            ->setTestX(null)
            ->setTestY(null)
            ->setNormalizedTrainX($trainImg)
            ->setNormalizedTrainY($trainLabel)
            ->setNormalizedTestX($testImg)
            ->setNormalizedTestY($testLabel);

        return $payload;
    }
}
