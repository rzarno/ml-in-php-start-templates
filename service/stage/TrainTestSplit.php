<?php

namespace service\stage;

use Interop\Polite\Math\Matrix\NDArray;
use League\Pipeline\StageInterface;
use Rindow\Math\Matrix\NDArrayPhp;
use service\model\Payload;

class TrainTestSplit implements StageInterface
{
    public function trainTestSplit(array $sequenceImg, array $sequenceLabel, int $imgWidth, int $imgHeight, int $numLayers, float $trainPart = 0.8): array
    {
        $count = count($sequenceImg);
        $split = (int) ($trainPart * $count);
        $trainImg = array_slice($sequenceImg, 0, $split);
        $testImg = array_slice($sequenceImg, $split);

        $trainLabel = array_slice($sequenceLabel, 0, $split);
        $testLabel = array_slice($sequenceLabel, $split);
        $trainImgCount = count($trainImg);
        $testImgCount = count($testImg);

        $trainImgNDArray = new NDArrayPhp($trainImg, NDArray::int16, [$trainImgCount, $numLayers, $imgWidth, $imgHeight]);
        $trainLabelNDArray = new NDArrayPhp($trainLabel, NDArray::int8, [$trainImgCount]);
        $testImgNDArray = new NDArrayPhp($testImg, NDArray::int16, [$testImgCount, $numLayers, $imgWidth, $imgHeight]);
        $testLabelNDArray = new NDArrayPhp($testLabel, NDArray::int8, [$testImgCount]);
        return [$trainImgNDArray, $testImgNDArray, $trainLabelNDArray, $testLabelNDArray];
    }

    /**
     * @param Payload $payload
     * @return Payload
     */
    public function __invoke($payload)
    {
        echo "split to train and test set\n";
        [$trainImg, $testImg, $trainLabel, $testLabel] = $this->trainTestSplit(
            $payload->getDataImg(),
            $payload->getDataLabel(),
            $payload->getConfigImgWidth(),
            $payload->getConfigImgHeight(),
            $payload->getConfigNumImgLayers()
        );

        $payload->setTrainImg($trainImg)
            ->setTrainLabel($trainLabel)
            ->setTestImg($testImg)
            ->setTestLabel($testLabel);

        echo "train=[". implode(',', $trainImg->shape()) . "]\n";
        echo "test=[". implode(',', $testImg->shape()) . "]\n";
        echo "batch_size={" . $payload->getConfigBatchSize() . "}\n";

        return $payload;
    }
}
