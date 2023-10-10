<?php

namespace service\model;

use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Extractors\ColumnPicker;
use Rubix\ML\Loggers\Screen;
use Rubix\ML\PersistentModel;

class PrognosePayload implements PayloadInterface
{
    private Labeled $dataset;
    private PersistentModel $estimator;
    private Screen $logger;

    public function getDataset(): Labeled
    {
        return $this->dataset;
    }

    public function setDataset(Labeled $dataset): PrognosePayload
    {
        $this->dataset = $dataset;
        return $this;
    }

    public function getEstimator(): PersistentModel
    {
        return $this->estimator;
    }

    public function setEstimator(PersistentModel $estimator): PrognosePayload
    {
        $this->estimator = $estimator;
        return $this;
    }

    public function getLogger(): Screen
    {
        return $this->logger;
    }

    public function setLogger(Screen $logger): PrognosePayload
    {
        $this->logger = $logger;
        return $this;
    }


}