<?php

namespace service\stage;

use League\Pipeline\StageInterface;
use Rindow\NeuralNetworks\Model\Sequential;
use service\model\CNNPayload;

class ModelExport implements StageInterface
{
    public function export(Sequential $model, string $modelFilePath)
    {
        $model->save($modelFilePath, $portable=true);
    }

    /**
     * @param CNNPayload $payload
     * @return CNNPayload
     */
    public function __invoke($payload)
    {
        echo "export model \n";
        $this->export($payload->getModel(), $payload->getConfigModelFilePath());

        return $payload;
    }
}
