<?php

namespace service\stage;

use Interop\Polite\Math\Matrix\NDArray;
use League\Pipeline\StageInterface;
use Rindow\Math\Matrix\MatrixOperator;
use Rindow\Math\Plot\Plot;
use Rindow\NeuralNetworks\Builder\NeuralNetworks;
use Rindow\NeuralNetworks\Model\Sequential;
use service\model\CNNPayload;

class PrognoseModelTraining implements StageInterface
{
    public function __construct(
        private readonly Plot $plt,
        private readonly MatrixOperator $matrixOperator,
        private readonly NeuralNetworks $neuralNetworks
    ) {
    }

    public function trainModel(
        Sequential $model,
        NDArray $trainX,
        NDArray $trainY,
        NDArray $testX,
        NDArray $testY,
        int $batchSize,
        int $epochs
    ) {
        $trainDataset = $this->neuralNetworks->data->NDArrayDataset(
            $trainX,
            tests: $trainY,
            batch_size: $batchSize,
            shuffle: true,
        );
        $start = time();
        $history = $model->fit(
            $trainDataset,
            epochs: $epochs,
            validation_data: [$testX, $testY]
        );
        $end = time();
        echo "processing took " . ($end - $start) / 60;
        $this->plt->plot($this->matrixOperator->array($history['accuracy']), null, null, 'accuracy');
        $this->plt->plot($this->matrixOperator->array($history['val_accuracy']), null, null, 'val_accuracy');
        $this->plt->plot($this->matrixOperator->array($history['loss']), null, null, 'loss');
        $this->plt->plot($this->matrixOperator->array($history['val_loss']), null, null, 'val_loss');
        $this->plt->legend();
    }

    /**
     * @param CNNPayload $payload
     * @return CNNPayload
     */
    public function __invoke($payload)
    {
        if ($payload->isConfigUseExistingModel()) {
            return $payload;
        }
        echo "training model ...\n";
        $this->trainModel(
            $payload->getModel(),
            $payload->getTrainX(),
            $payload->getTrainY(),
            $payload->getTestX(),
            $payload->getTestY(),
            $payload->getConfigBatchSize(),
            $payload->getConfigNumEpochs()
        );

        return $payload;
    }
}
