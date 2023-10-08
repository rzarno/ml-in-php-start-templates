<?php

namespace service\model;

use Interop\Polite\Math\Matrix\NDArray;
use Rindow\NeuralNetworks\Model\Sequential;

class CNNPayload extends Payload
{
    private ?array $importedData;
    private array $configInputShape;
    private NDArray $normalizedTrainX;
    private NDArray $normalizedTestX;
    private NDArray $normalizedTrainY;
    private NDArray $normalizedTestY;
    private Sequential $model;

    public function __construct(
        private readonly string $configModelVersion,
        private readonly int $configNumEpochs,
        private readonly int $configBatchSize,
        private readonly int $configImgWidth,
        private readonly int $configImgHeight,
        private readonly int $configNumImgLayers,
        private readonly string $configModelFilePath,
        private readonly array $configClassNames,
        private readonly bool $configUseExistingModel,
        private readonly int $cropFromTop = 0,
        private readonly int $imputeIterations = 0
    ) {
        $this->configInputShape = [$this->configImgWidth, $this->configImgHeight, $this->configNumImgLayers];
        parent::__construct(
            $configModelVersion,
            $configNumEpochs,
            $configBatchSize,
            $configModelFilePath,
            $configUseExistingModel
        );
    }

    public function getImportedData(): ?array
    {
        return $this->importedData;
    }

    public function setImportedData(?array $importedData): CNNPayload
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

    public function getConfigImgWidth(): int
    {
        return $this->configImgWidth;
    }

    public function getConfigImgHeight(): int
    {
        return $this->configImgHeight;
    }

    public function getCropFromTop(): int
    {
        return $this->cropFromTop;
    }

    public function getImputeIterations(): int
    {
        return $this->imputeIterations;
    }

    public function getConfigNumImgLayers(): int
    {
        return $this->configNumImgLayers;
    }

    public function getConfigModelFilePath(): string
    {
        return $this->configModelFilePath;
    }

    public function getConfigInputShape(): array
    {
        return $this->configInputShape;
    }

    public function getConfigClassNames(): array
    {
        return $this->configClassNames;
    }

    public function isConfigUseExistingModel(): bool
    {
        return $this->configUseExistingModel;
    }

    public function getNormalizedTrainX(): NDArray
    {
        return $this->normalizedTrainX;
    }

    public function setNormalizedTrainX(NDArray $normalizedTrainX): CNNPayload
    {
        $this->normalizedTrainX = $normalizedTrainX;
        return $this;
    }

    public function getNormalizedTestX(): NDArray
    {
        return $this->normalizedTestX;
    }

    public function setNormalizedTestX(NDArray $normalizedTestX): CNNPayload
    {
        $this->normalizedTestX = $normalizedTestX;
        return $this;
    }

    public function getNormalizedTrainY(): NDArray
    {
        return $this->normalizedTrainY;
    }

    public function setNormalizedTrainY(NDArray $normalizedTrainY): CNNPayload
    {
        $this->normalizedTrainY = $normalizedTrainY;
        return $this;
    }

    public function getNormalizedTestY(): NDArray
    {
        return $this->normalizedTestY;
    }

    public function setNormalizedTestY(NDArray $normalizedTestY): CNNPayload
    {
        $this->normalizedTestY = $normalizedTestY;
        return $this;
    }

    public function getModel(): Sequential
    {
        return $this->model;
    }

    public function setModel(Sequential $model): CNNPayload
    {
        $this->model = $model;
        return $this;
    }
}
