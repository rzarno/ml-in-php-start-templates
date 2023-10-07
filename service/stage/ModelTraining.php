<?php

namespace service\stage;

use Interop\Polite\Math\Matrix\NDArray;
use League\Pipeline\StageInterface;
use Rindow\Math\Matrix\MatrixOperator;
use Rindow\Math\Plot\Plot;
use Rindow\NeuralNetworks\Builder\NeuralNetworks;
use Rindow\NeuralNetworks\Model\Sequential;
use service\model\Payload;

class ModelTraining implements StageInterface
{
    public function __construct(
        private readonly Plot $plt,
        private readonly MatrixOperator $matrixOperator,
        private readonly NeuralNetworks $neuralNetworks
    ) {
    }

    public function trainModel(
        Sequential $model,
        NDArray $trainImg,
        NDArray $trainLabel,
        NDArray $testImg,
        NDArray $testLabel,
        int $batchSize,
        int $epochs
    ) {
        $trainDataset = $this->neuralNetworks->data->ImageDataGenerator(
            $trainImg,
            tests: $trainLabel,
            batch_size: $batchSize,
            shuffle: true,
            height_shift: 2,
            width_shift: 2,
            vertical_flip: true,
            horizontal_flip: true
        );
        $start = time();
        $history = $model->fit(
            $trainDataset,
            epochs: $epochs,
            validation_data: [$testImg, $testLabel]
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
     * @param Payload $payload
     * @return Payload
     */
    public function __invoke($payload)
    {
        if ($payload->isConfigUseExistingModel()) {
            return $payload;
        }
        echo "training model ...\n";
        $this->trainModel(
            $payload->getModel(),
            $payload->getNormalizedTrainImg(),
            $payload->getNormalizedTrainLabel(),
            $payload->getNormalizedTestImg(),
            $payload->getNormalizedTestLabel(),
            $payload->getConfigBatchSize(),
            $payload->getConfigNumEpochs()
        );

        return $payload;
    }
}
