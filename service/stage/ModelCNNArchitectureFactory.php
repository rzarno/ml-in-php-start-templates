<?php

namespace service\stage;

use League\Pipeline\StageInterface;
use Rindow\NeuralNetworks\Builder\NeuralNetworks;
use Rindow\NeuralNetworks\Model\Sequential;
use service\model\CNNPayload;

class ModelCNNArchitectureFactory implements StageInterface
{
    public function __construct(
        private readonly NeuralNetworks $neuralNetworks
    ) {
    }

    public function rinbowCNN(array $inputShape, int $numClasses): Sequential
    {
        $nn = $this->neuralNetworks;
        $model = $nn->models()->Sequential([
            $nn->layers()->Conv2D(
                $filters = 64,
                $kernel_size = 5,
                input_shape: $inputShape,
                kernel_initializer: 'he_normal'
            ),
            $nn->layers()->BatchNormalization(),
            $nn->layers()->Activation('relu'),
            $nn->layers()->Conv2D(
                $filters = 64,
                $kernel_size = 5,
                kernel_initializer: 'he_normal'
            ),
            $nn->layers()->MaxPooling2D(),
            $nn->layers()->Conv2D(
                $filters = 128,
                $kernel_size = 5,
                kernel_initializer: 'he_normal'
            ),
            $nn->layers()->BatchNormalization(),
            $nn->layers()->Activation('relu'),
            $nn->layers()->Conv2D(
                $filters = 128,
                $kernel_size = 3,
                kernel_initializer: 'he_normal'
            ),
            $nn->layers()->MaxPooling2D(),
            $nn->layers()->Conv2D(
                $filters = 256,
                $kernel_size = 3,
                kernel_initializer: 'he_normal',
                activation: 'relu'
            ),
            $nn->layers()->GlobalAveragePooling2D(),
            $nn->layers()->Dense(
                $units = 512,
                kernel_initializer: 'he_normal'
            ),
            $nn->layers()->BatchNormalization(),
            $nn->layers()->Activation('relu'),
            $nn->layers()->Dense(
                $units = $numClasses,
                activation: 'softmax'
            ),
        ]);

        $model->compile(
            loss: 'sparse_categorical_crossentropy',
            optimizer: 'adam',
        );
        $model->summary();
        return $model;
    }

    public function createNvidiaCNNDave2(array $inputShape, int $numClasses): Sequential
    {
        $nn = $this->neuralNetworks;
        $model = $nn->models()->Sequential([
            $nn->layers()->Conv2D(
                $filters = 64,
                $kernel_size = 5,
                input_shape: $inputShape,
                kernel_initializer: 'he_normal'
            ),
            $nn->layers()->BatchNormalization(),
            $nn->layers()->Activation('relu'),
            $nn->layers()->Conv2D(
                $filters = 64,
                $kernel_size = 5,
                kernel_initializer: 'he_normal'
            ),
            $nn->layers()->MaxPooling2D(),
            $nn->layers()->Conv2D(
                $filters = 128,
                $kernel_size = 5,
                kernel_initializer: 'he_normal'
            ),
            $nn->layers()->BatchNormalization(),
            $nn->layers()->Activation('relu'),
            $nn->layers()->Conv2D(
                $filters = 128,
                $kernel_size = 3,
                kernel_initializer: 'he_normal'
            ),
            $nn->layers()->MaxPooling2D(),
            $nn->layers()->Conv2D(
                $filters = 256,
                $kernel_size = 3,
                kernel_initializer: 'he_normal',
                activation: 'relu'
            ),
            $nn->layers()->GlobalAveragePooling2D(),

            $nn->layers()->Dense(
                $units=512,
                kernel_initializer: 'he_normal'
            ),
            $nn->layers()->BatchNormalization(),
            $nn->layers()->Activation('relu'),
            $nn->layers()->Flatten(),
            $nn->layers()->Dropout(0.2),
            $nn->layers()->Dense($units = 100, activation: 'relu'),
            $nn->layers()->Dense($units = 50, activation: 'relu'),
            $nn->layers()->Dense(
                $units = $numClasses,
                activation: 'softmax'
            ),
        ]);

        $model->compile(
            loss: 'sparse_categorical_crossentropy',
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
            $model = $this->createNvidiaCNNDave2($payload->getConfigInputShape(), count($payload->getConfigClassNames()));
        }
        $payload->setModel($model);

        return $payload;
    }
}
