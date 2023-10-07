<?php

namespace service\stage;

use Interop\Polite\Math\Matrix\NDArray;
use League\Pipeline\StageInterface;
use Rindow\Math\Matrix\MatrixOperator;
use Rindow\Math\Plot\Plot;
use service\model\Payload;

class ModelEvaluator implements StageInterface
{
    public function __construct(
        private readonly Plot $plt,
        private readonly MatrixOperator $matrixOperator
    ) {
    }

    public function showResulPlot(
        $predicts,
        $images,
        $inputShape,
        $labels,
        $classNames,
        $numClasses
    ) {
        $this->plt->setConfig([
            'frame.xTickLength'=>0,'title.position'=>'down','title.margin'=>0,]);
        [,$axes] = $this->plt->subplots(4, 4);
        foreach ($predicts as $i => $predict) {
            $axes[$i*2]->imshow(
                $images[$i]->reshape($inputShape),
                null,
                null,
                null,
                $origin='upper'
            );
            $axes[$i*2]->setFrame(false);
            $label = $labels[$i];
            $axes[$i*2]->setTitle($classNames[$label]."($label)");
            $axes[$i*2+1]->bar($this->matrixOperator->arange($numClasses), $predict);
        }

        $this->plt->show();
    }

    public function evaluate(NDArray $predicts, NDArray $labels)
    {
        $max = [];
        foreach ($predicts as $single) {
            $max[] = array_keys($single->toArray(), max($single->toArray()))[0];
        }

        $count = count($max);
        $count1 = 0;
        $count2 = 0;
        $count3 = 0;
        $result = 0;
        $result1 = 0;
        $result2 = 0;
        $result3 = 0;
        $resultDetails = [];
        $labelsArr = $labels->toArray();
        foreach ($max as $key => $value) {
            $resultDetails[] = ['real'=> $labelsArr[$key], 'pred' => $value];
            match ($labelsArr[$key]) {
                1 => $count1++,
                2 => $count2++,
                3 => $count3++,
                default => null
            };

            if ($value === $labelsArr[$key]) {
                $result++;
                match($value) {
                    1 => $result1++,
                    2 => $result2++,
                    3 => $result3++,
                    default => null
                };
            }
        }

        var_dump('correct predictions: ' . $result . ', ' . ($result/$count));
        var_dump('correct predictions 1: ' . $result1 . '/' . $count1 . ', ' . ($result1/$count1));
        var_dump('correct predictions 2: ' . $result2 . '/' . $count2 . ', ' . ($result2/$count2));
        var_dump('correct predictions 3: ' . $result3 . '/' . $count3 . ', ' . ($result3/$count3));
        var_dump($resultDetails);
    }

    /**
     * @param Payload $payload
     * @return Payload
     */
    public function __invoke($payload)
    {
        echo "evaluate model \n";
        $images = $payload->getNormalizedTestImg()[[0,7]];
        $labels = $payload->getNormalizedTestLabel()[[0,7]];
        $predicts = $payload->getModel()->predict($images);

        $this->showResulPlot(
            $predicts,
            $images,
            $payload->getConfigInputShape(),
            $labels,
            $payload->getConfigClassNames(),
            count($payload->getConfigClassNames())
        );

        $images = $payload->getNormalizedTestImg()[[200,400]];
        $labels = $payload->getNormalizedTestLabel()[[200,400]];
        $predicts = $payload->getModel()->predict($images);
        $this->evaluate($predicts, $labels);

        return $payload;
    }
}
