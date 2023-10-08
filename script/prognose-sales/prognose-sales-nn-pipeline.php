<?php

require __DIR__ . '/../../vendor/autoload.php';

use League\Pipeline\FingersCrossedProcessor;
use League\Pipeline\Pipeline;
use Rindow\Math\Matrix\MatrixOperator;
use Rindow\Math\Plot\Plot;
use Rindow\Math\Plot\Renderer\GDDriver;
use Rindow\NeuralNetworks\Builder\NeuralNetworks;
use service\model\PrognosePayload;
use service\stage\ModelExport;
use service\stage\NeuralNetworkModelFactory;
use service\stage\PrognoseDataProvider;
use service\stage\PrognoseModelEvaluator;
use service\stage\PrognoseModelTraining;
use service\stage\PrognoseTrainTestSplit;

$matrixOperator = new MatrixOperator();
$plot = new Plot(matrixOperator: $matrixOperator, renderer: new GDDriver(skipRunViewer: true));
$dataProvider = new PrognoseDataProvider();
$neuralNetworks = new NeuralNetworks($matrixOperator);
$cnnModelFactory = new NeuralNetworkModelFactory($neuralNetworks);
$modelTrain = new PrognoseModelTraining($plot, $matrixOperator, $neuralNetworks);
$resultsEvaluator = new PrognoseModelEvaluator();
$trainTestSplit = new PrognoseTrainTestSplit();
$modelExport = new ModelExport();

$payload = new PrognosePayload(
    $configModelVersion = '1.0',
    $configEpochs = 30,
    $configBatchSize = 64,
    $configModelFilePath = __DIR__ . "/../../model/prognose-{$configModelVersion}.model",
    $configUseExistingModel = false,
);

$pipeline = (new Pipeline(new FingersCrossedProcessor()))
    ->pipe($dataProvider)
    ->pipe($trainTestSplit)
    ->pipe($cnnModelFactory)
    ->pipe($modelTrain)
    ->pipe($modelExport)
    ->pipe($resultsEvaluator);

$pipeline->process($payload);
