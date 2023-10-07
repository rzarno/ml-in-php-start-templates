<?php

namespace service\model;

use Interop\Polite\Math\Matrix\NDArray;
use Rindow\Math\Matrix\NDArrayPhp;
use Rindow\NeuralNetworks\Model\Sequential;

class Payload
{
    private ?array $importedData;
    private array $dataImg;
    private array $dataLabel;
    private array $configInputShape;
    private ?NDArrayPhp $trainImg;
    private ?NDArrayPhp $testImg;
    private ?NDArrayPhp $trainLabel;
    private ?NDArrayPhp $testLabel;
    private NDArray $normalizedTrainImg;
    private NDArray $normalizedTestImg;
    private NDArray $normalizedTrainLabel;
    private NDArray $normalizedTestLabel;
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

    public function getDataImg(): array
    {
        return $this->dataImg;
    }

    public function setDataImg(array $dataImg): self
    {
        $this->dataImg = $dataImg;
        return $this;
    }

    public function getDataLabel(): array
    {
        return $this->dataLabel;
    }

    public function setDataLabel(array $dataLabel): self
    {
        $this->dataLabel = $dataLabel;
        return $this;
    }

    public function getTrainImg(): NDArrayPhp
    {
        return $this->trainImg;
    }

    public function setTrainImg(?NDArrayPhp $trainImg): Payload
    {
        $this->trainImg = $trainImg;
        return $this;
    }

    public function getTestImg(): NDArrayPhp
    {
        return $this->testImg;
    }

    public function setTestImg(?NDArrayPhp $testImg): Payload
    {
        $this->testImg = $testImg;
        return $this;
    }

    public function getTrainLabel(): NDArrayPhp
    {
        return $this->trainLabel;
    }

    public function setTrainLabel(?NDArrayPhp $trainLabel): Payload
    {
        $this->trainLabel = $trainLabel;
        return $this;
    }

    public function getTestLabel(): NDArrayPhp
    {
        return $this->testLabel;
    }

    public function setTestLabel(?NDArrayPhp $testLabel): Payload
    {
        $this->testLabel = $testLabel;
        return $this;
    }

    public function getNormalizedTrainImg(): NDArray
    {
        return $this->normalizedTrainImg;
    }

    public function setNormalizedTrainImg(NDArray $normalizedTrainImg): Payload
    {
        $this->normalizedTrainImg = $normalizedTrainImg;
        return $this;
    }

    public function getNormalizedTestImg(): NDArray
    {
        return $this->normalizedTestImg;
    }

    public function setNormalizedTestImg(NDArray $normalizedTestImg): Payload
    {
        $this->normalizedTestImg = $normalizedTestImg;
        return $this;
    }

    public function getNormalizedTrainLabel(): NDArray
    {
        return $this->normalizedTrainLabel;
    }

    public function setNormalizedTrainLabel(NDArray $normalizedTrainLabel): Payload
    {
        $this->normalizedTrainLabel = $normalizedTrainLabel;
        return $this;
    }

    public function getNormalizedTestLabel(): NDArray
    {
        return $this->normalizedTestLabel;
    }

    public function setNormalizedTestLabel(NDArray $normalizedTestLabel): Payload
    {
        $this->normalizedTestLabel = $normalizedTestLabel;
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
}
