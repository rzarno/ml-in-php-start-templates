<?php

namespace service\stage;

use League\Pipeline\StageInterface;
use Rubix\ML\Extractors\ColumnPicker;
use Rubix\ML\Extractors\CSV;
use function Rubix\ML\array_transpose;

class PrognoseModelEvaluator implements StageInterface
{
    public function __invoke($payload)
    {
        $predictions = $payload->getEstimator()->predict($payload->getDataset());
        $extractor = new ColumnPicker(new CSV('../../data/sales/sales_processed.csv', true), ['id']);
        $ids = array_column(iterator_to_array($extractor), 'Id');
        array_unshift($ids, 'Id');
        array_unshift($predictions, 'sales');
        $extractor = new CSV('predictions.csv');
        $extractor->export(array_transpose([$ids, $predictions]));
        $payload->getLogger()->info('Predictions saved to predictions.csv');

        $dataset = $payload->getDataset();
        $real = $dataset->labels();
        array_shift($predictions);
        array_shift($real);
        $sum = 0;
        $sumSquared = 0;
        foreach ($predictions as $key => $prediction) {
            if ($key < 450) {
                continue;
            }
            if ($key == 500) {
                break;
            }
            $sum += abs($real[$key] - $prediction);
            $sumSquared += pow($real[$key] - $prediction, 2);
        }
        $payload->getLogger()->info('Mean absolute error (chosen 50 days): ' . $sum / count($predictions));
        $payload->getLogger()->info('Root mean absolute error (chosen 50 days): ' . sqrt($sumSquared / count($predictions)));

        return $payload;
    }
}
