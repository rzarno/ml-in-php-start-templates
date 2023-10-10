<?php

namespace service\stage;

use League\Pipeline\StageInterface;
use Rubix\ML\Transformers\MissingDataImputer;
use Rubix\ML\Transformers\NumericStringConverter;

class PrognoseDataPreprocess implements StageInterface
{
    public function __invoke($payload)
    {
        $payload->getDataset()->apply(new NumericStringConverter())
            ->apply(new MissingDataImputer())
            ->transformLabels('intval');
        return $payload;
    }
}