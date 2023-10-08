<?php

namespace service\stage;

use League\Pipeline\StageInterface;
use Rindow\NeuralNetworks\Builder\NeuralNetworks;
use Rindow\NeuralNetworks\Model\Sequential;
use service\model\CNNPayload;

class NeuralNetworkModelFactory implements StageInterface
{
    public function __construct(
        private readonly NeuralNetworks $neuralNetworks
    ) {
    }

    public function createDeepNeuralNetwork(): Sequential
    {
        $nn = $this->neuralNetworks;
        $model = $nn->models()->Sequential([
            $nn->layers()->Input(shape:  [7]),
            $nn->layers()->Dense($units = 250, activation: 'relu'),
            $nn->layers()->Dense($units = 200, activation: 'relu'),
            $nn->layers()->Dense($units = 100, activation: 'relu'),
            $nn->layers()->Dense($units = 50, activation: 'relu'),
            $nn->layers()->Dense($units = 30, activation: 'relu'),
            $nn->layers()->Dense($units = 1, activation: 'sigmoid'),
        ]);

        $model->compile(
            loss: $nn->losses()->MeanSquaredError(),
            optimizer: 'adam',
        );
        $model->summary();
        return $model;
    }

    /**
     * @param CNNPayload $payload
     * @return CNNPayload
     */
    public function __invoke($payload)
    {
        if ($payload->isConfigUseExistingModel()) {
            echo "loading model ...\n";
            $model = $this->neuralNetworks->models()->loadModel($payload->getConfigModelFilePath());
            $model->summary();
        } else {
            echo "building model...\n";
            $model = $this->createDeepNeuralNetwork();
        }
        $payload->setModel($model);

        return $payload;
    }
}
