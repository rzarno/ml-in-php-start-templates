<?php

require __DIR__ . '/../../vendor/autoload.php';

use League\Pipeline\FingersCrossedProcessor;
use League\Pipeline\Pipeline;
use Rubix\ML\Loggers\Screen;

use service\model\PrognosePayload;
use service\stage\PrognoseDataPreprocess;
use service\stage\PrognoseDataProvider;
use service\stage\PrognoseModelEvaluator;
use service\stage\PrognoseModelTraining;

ini_set('memory_limit', '-1');

$logger = new Screen();

$payload = new PrognosePayload();
$payload->setLogger($logger);

$dataProvider = new PrognoseDataProvider();
$dataPreprocess = new PrognoseDataPreprocess();
$modelTraining = new PrognoseModelTraining();
$modelEvaluator = new PrognoseModelEvaluator();

$pipeline = (new Pipeline(new FingersCrossedProcessor()))
    ->pipe($dataProvider)
    ->pipe($dataPreprocess)
    ->pipe($modelTraining)
    ->pipe($modelEvaluator);

$pipeline->process($payload);
