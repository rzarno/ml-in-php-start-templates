<?php

namespace service\model;

use Interop\Polite\Math\Matrix\NDArray;
use Rindow\Math\Matrix\NDArrayPhp;
use Rindow\NeuralNetworks\Model\Sequential;

class Payload implements PayloadInterface
{
    private ?array $importedData;
    private array $dataX;
    private array $dataY;
    private ?NDArrayPhp $trainX;
    private ?NDArrayPhp $testX;
    private ?NDArrayPhp $trainY;
    private ?NDArrayPhp $testY;
    private NDArray $normalizedTrainX;
    private NDArray $normalizedTestX;
    private NDArray $normalizedTrainY;
    private NDArray $normalizedTestY;
    private Sequential $model;

    public function __construct(
        private readonly string $configModelVersion,
        private readonly int $configNumEpochs,
        private readonly int $configBatchSize,
        private readonly string $configModelFilePath,
        private readonly bool $configUseExistingModel
    ) {
    }

    public function getImportedData(): ?array
    {
        return $this->importedData;
    }

    public function setImportedData(?array $importedData): Payload
    {
        $this->importedData = $importedData;
        return $this;
    }

    public function getConfigModelVersion(): string
    {
        return $this->configModelVersion;
    }

    public function getConfigNumEpochs(): int
    {
        return $this->configNumEpochs;
    }

    public function getConfigBatchSize(): int
    {
        return $this->configBatchSize;
    }

    public function getConfigModelFilePath(): string
    {
        return $this->configModelFilePath;
    }

    public function isConfigUseExistingModel(): bool
    {
        return $this->configUseExistingModel;
    }

    public function getDataY(): array
    {
        return $this->dataY;
    }

    public function setDataY(array $dataY): self
    {
        $this->dataY = $dataY;
        return $this;
    }

    public function getTrainY(): NDArrayPhp
    {
        return $this->trainY;
    }

    public function setTrainY(?NDArrayPhp $trainY): Payload
    {
        $this->trainY = $trainY;
        return $this;
    }

    public function getTestY(): NDArrayPhp
    {
        return $this->testY;
    }

    public function setTestY(?NDArrayPhp $testY): Payload
    {
        $this->testY = $testY;
        return $this;
    }

    public function getNormalizedTrainX(): NDArray
    {
        return $this->normalizedTrainX;
    }

    public function setNormalizedTrainX(NDArray $normalizedTrainX): Payload
    {
        $this->normalizedTrainX = $normalizedTrainX;
        return $this;
    }

    public function getNormalizedTestX(): NDArray
    {
        return $this->normalizedTestX;
    }

    public function setNormalizedTestX(NDArray $normalizedTestX): Payload
    {
        $this->normalizedTestX = $normalizedTestX;
        return $this;
    }

    public function getNormalizedTrainY(): NDArray
    {
        return $this->normalizedTrainY;
    }

    public function setNormalizedTrainY(NDArray $normalizedTrainY): Payload
    {
        $this->normalizedTrainY = $normalizedTrainY;
        return $this;
    }

    public function getNormalizedTestY(): NDArray
    {
        return $this->normalizedTestY;
    }

    public function setNormalizedTestY(NDArray $normalizedTestY): Payload
    {
        $this->normalizedTestY = $normalizedTestY;
        return $this;
    }

    public function getModel(): Sequential
    {
        return $this->model;
    }

    public function setModel(Sequential $model): Payload
    {
        $this->model = $model;
        return $this;
    }

    public function getDataX(): array
    {
        return $this->dataX;
    }

    public function setDataX(array $dataX): self
    {
        $this->dataX = $dataX;
        return $this;
    }

    public function getTrainX(): NDArrayPhp
    {
        return $this->trainX;
    }

    public function setTrainX(?NDArrayPhp $trainX): Payload
    {
        $this->trainX = $trainX;
        return $this;
    }

    public function getTestX(): NDArrayPhp
    {
        return $this->testX;
    }

    public function setTestX(?NDArrayPhp $testX): Payload
    {
        $this->testX = $testX;
        return $this;
    }
}
