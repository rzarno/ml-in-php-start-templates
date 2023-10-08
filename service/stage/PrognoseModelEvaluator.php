<?php

namespace service\stage;

use Interop\Polite\Math\Matrix\NDArray;
use League\Pipeline\StageInterface;
use service\model\PrognosePayload;

class PrognoseModelEvaluator implements StageInterface
{
    public function evaluate(NDArray $predicts, NDArray $labels)
    {
        $predictsArr = $predicts->toArray();
        $labelsArr = $labels->toArray();
        foreach ($predictsArr as $key => $single) {
            var_dump($single, $labelsArr[$key]);
        }
    }

    /**
     * @param PrognosePayload $payload
     * @return PrognosePayload
     */
    public function __invoke($payload)
    {
        echo "evaluate model \n";
        $data = $payload->getTestX()[[200,210]];
        $labels = $payload->getTestY()[[200,210]];
        $predicts = $payload->getModel()->predict($data);

        $this->evaluate($predicts, $labels);

        return $payload;
    }
}
