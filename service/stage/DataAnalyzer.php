<?php

namespace service\stage;

use League\Pipeline\StageInterface;
use Rindow\Math\Matrix\MatrixOperator;
use Rindow\Math\Plot\Plot;
use service\model\CNNPayload;

class DataAnalyzer implements StageInterface
{
    public function __construct(
        private readonly Plot $plt,
        private readonly MatrixOperator $matrixOperator
    ) {
    }

    public function analyzeData(array $labels)
    {
        echo "analyze labels distribution\n";
        $labels = array_count_values($labels);
        $this->plt->bar(array_keys($labels), $this->matrixOperator->array($labels));
        $this->plt->show();
    }

    /**
     * @param CNNPayload $payload
     * @return CNNPayload
     */
    public function __invoke($payload)
    {
        $this->analyzeData($payload->getDataY());
        return $payload;
    }
}
