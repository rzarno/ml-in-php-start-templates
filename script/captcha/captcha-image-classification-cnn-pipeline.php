<?php

require __DIR__ . '/../../vendor/autoload.php';

use League\Pipeline\FingersCrossedProcessor;
use League\Pipeline\Pipeline;
use Rindow\Math\Matrix\MatrixOperator;
use Rindow\Math\Plot\Plot;
use Rindow\Math\Plot\Renderer\GDDriver;
use Rindow\NeuralNetworks\Builder\NeuralNetworks;
use service\CaptchaCharEncoder;
use service\ImageTransform;
use service\model\CNNPayload;
use service\stage\CaptchaImageCharExtractor;
use service\stage\CaptchaImageDataProvider;
use service\stage\DataAnalyzer;
use service\stage\ImagePreprocesor;
use service\stage\ModelCNNArchitectureFactory;
use service\stage\ImageModelEvaluator;
use service\stage\ModelExport;
use service\stage\CaptchaModelTraining;
use service\stage\CaptchaTrainTestSplit;

$matrixOperator = new MatrixOperator();
$plot = new Plot(matrixOperator: $matrixOperator, renderer: new GDDriver(skipRunViewer: true));
$dataProvider = new CaptchaImageDataProvider();
$dataAnalyzer = new DataAnalyzer($plot, $matrixOperator);
$charImageExtractor = new CaptchaImageCharExtractor(new ImageTransform(), new CaptchaCharEncoder());
$neuralNetworks = new NeuralNetworks($matrixOperator);
$cnnModelFactory = new ModelCNNArchitectureFactory($neuralNetworks);
$modelTrain = new CaptchaModelTraining($plot, $matrixOperator, $neuralNetworks);
$resultsEvaluator = new ImageModelEvaluator($plot, $matrixOperator);
$trainTestSplit = new CaptchaTrainTestSplit();
$imagePreprocessor = new ImagePreprocesor($matrixOperator);
$modelExport = new ModelExport();

$payload = new CNNPayload(
    $configModelVersion = '1.0',
    $configEpochs = 30,
    $configBatchSize = 64,
    $configImgWidth = 40,
    $configImgHeight = 50,
    $configNumImgLayers = 3,
    $configModelFilePath = __DIR__ . "/../../model/char-classification-with-cnn-{$configModelVersion}.model",
    $configClassNames = ['6', '2', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'W', 'V', 'X', 'Y', 'Z'],
    $configUseExistingModel = false,
    $cropFromTop = 0,
    $imputeIterations = 0
);

$pipeline = (new Pipeline(new FingersCrossedProcessor()))
    ->pipe($dataProvider)
    ->pipe($charImageExtractor)
    ->pipe($dataAnalyzer)
    ->pipe($trainTestSplit)
    ->pipe($imagePreprocessor)
    ->pipe($cnnModelFactory)
    ->pipe($modelTrain)
    ->pipe($modelExport)
    ->pipe($resultsEvaluator);

$pipeline->process($payload);
