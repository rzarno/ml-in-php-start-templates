<?php

namespace service\stage;

use League\Pipeline\StageInterface;

class PrognoseDataProvider implements StageInterface
{
    public function importData(): array
    {
        echo "importing data\n";
        $parentPath = '../../data/sales';
        $file = fopen($parentPath . '/sales_processed.csv', 'r');
        $records = [];
        $X = [];
        $y = [];
        $isFirstLine = true;
        while (($line = fgetcsv($file)) !== FALSE) {
            if ($isFirstLine) {
                $isFirstLine = false;
                continue;
            }
            $row = [];
            $row['sales'] = (int) $line[0];
            $row['day'] = (int) $line[1];
            $row['year'] = (int) $line[2];
            $row['day_week'] = (int) $line[3];
            $row['month'] = (int) $line[4];
            $row['is_holiday'] = (int) $line[5];
            $row['prev_year'] = (int) $line[6];
            $row['prev_week'] = (int) $line[7];
            $records[] = $row;
            $X[] = [$row['day'], $row['year'], $row['day_week'], $row['month'], $row['is_holiday'], $row['prev_year'], $row['prev_week']];
            $y[] = $row['sales'];
        }
        fclose($file);
        return [$records, $X, $y];
    }

    public function __invoke($payload)
    {
        [$records, $X, $y] = $this->importData();
        $payload->setImportedData($records)->setDataX($X)->setDataY($y);

        return $payload;
    }
}