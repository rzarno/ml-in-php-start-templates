<?php

namespace service\model;

use Interop\Polite\Math\Matrix\NDArray;
use Rindow\Math\Matrix\NDArrayPhp;
use Rindow\NeuralNetworks\Model\Sequential;

interface PayloadInterface
{
    public function getImportedData(): ?array;
    public function setImportedData(?array $importedData): Payload;
    public function getConfigModelVersion(): string;
    public function getConfigNumEpochs(): int;
    public function getConfigBatchSize(): int;
    public function getConfigModelFilePath(): string;
    public function isConfigUseExistingModel(): bool;
    public function getDataY(): array;
    public function setDataY(array $dataLabel): self;
    public function getTrainY(): NDArrayPhp;
    public function setTrainY(?NDArrayPhp $trainLabel): Payload;
    public function getTestY(): NDArrayPhp;
    public function setTestY(?NDArrayPhp $testLabel): Payload;
    public function getNormalizedTrainY(): NDArray;
    public function setNormalizedTrainY(NDArray $normalizedTrainLabel): Payload;
    public function getNormalizedTestY(): NDArray;
    public function setNormalizedTestY(NDArray $normalizedTestLabel): Payload;
    public function getModel(): Sequential;
    public function setModel(Sequential $model): Payload;

}