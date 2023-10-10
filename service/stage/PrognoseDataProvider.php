<?php

namespace service\stage;

use League\Pipeline\StageInterface;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Extractors\ColumnPicker;
use Rubix\ML\Extractors\CSV;

class PrognoseDataProvider implements StageInterface
{
    public function __invoke($payload)
    {
        $payload->getLogger()->info('Loading data into memory');

        $extractor = new ColumnPicker(new CSV('../../data/sales/sales_processed.csv', true), [
            'sales','day','year','day_week','month','is_holiday','prev_week','prev_year'
        ]);
        $payload->setDataset(Labeled::fromIterator($extractor));

        return $payload;
    }
}