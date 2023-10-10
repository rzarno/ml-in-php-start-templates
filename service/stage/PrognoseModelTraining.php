<?php

namespace service\stage;

use League\Pipeline\StageInterface;
use Rubix\ML\Extractors\CSV;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Regressors\GradientBoost;
use Rubix\ML\Regressors\RegressionTree;
use Rubix\ML\Transformers\MissingDataImputer;
use Rubix\ML\Transformers\NumericStringConverter;
use service\model\PrognosePayload;

class PrognoseModelTraining implements StageInterface
{
    public function __invoke($payload)
    {
        $payload->getDataset()->apply(new NumericStringConverter())
            ->apply(new MissingDataImputer());

        $estimator = new PersistentModel(
            new GradientBoost(new RegressionTree(4), 0.1),
            new Filesystem('sales.rbx', true)
        );
        $estimator->setLogger($payload->getLogger());
        $estimator->train($payload->getDataset());

        $extractor = new CSV('progress.csv', true);

        $extractor->export($estimator->steps());

        $payload->getLogger()->info('Progress saved to progress.csv');

        if (strtolower(readline('Save this model? (y|[n]): ')) === 'y') {
            $estimator->save();
        }
        $payload->setEstimator($estimator);
        return $payload;
    }
}
