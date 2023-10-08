<?php

namespace service\stage;

use Interop\Polite\Math\Matrix\NDArray;
use League\Pipeline\StageInterface;
use Rindow\Math\Matrix\NDArrayPhp;
use service\model\CNNPayload;

class PrognoseTrainTestSplit implements StageInterface
{
    public function trainTestSplit(
        array $sequenceImg,
        array $sequenceLabel,
        float $trainPart = 0.8
    ): array
    {
        $count = count($sequenceImg);
        $split = (int) ($trainPart * $count);
        $trainX = array_slice($sequenceImg, 0, $split);
        $testX = array_slice($sequenceImg, $split);

        $trainY = array_slice($sequenceLabel, 0, $split);
        $testY = array_slice($sequenceLabel, $split);
        $trainYCount = count($trainY);
        $testYCount = count($testY);

        $trainXNDArray = new NDArrayPhp($trainX, NDArray::float32, [$trainYCount, 7]);
        $trainYNDArray = new NDArrayPhp($trainY, NDArray::float32, [$trainYCount, 1]);
        $testXNDArray = new NDArrayPhp($testX, NDArray::float32, [$testYCount, 7]);
        $testYNDArray = new NDArrayPhp($testY, NDArray::float32, [$testYCount, 1]);
        return [$trainXNDArray, $testXNDArray, $trainYNDArray, $testYNDArray];
    }

    /**
     * @param CNNPayload $payload
     * @return CNNPayload
     */
    public function __invoke($payload)
    {
        echo "split to train and test set\n";
        [$trainX, $testX, $trainY, $testY] = $this->trainTestSplit(
            $payload->getDataX(),
            $payload->getDataY()
        );

        $payload->setTrainX($trainX)
            ->setTrainY($trainY)
            ->setTestX($testX)
            ->setTestY($testY);

        echo "train=[". implode(',', $trainX->shape()) . "]\n";
        echo "test=[". implode(',', $testX->shape()) . "]\n";
        echo "batch_size={" . $payload->getConfigBatchSize() . "}\n";

        return $payload;
    }
}
