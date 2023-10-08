<?php

namespace service\stage;

use League\Pipeline\StageInterface;
use service\model\CNNPayload;

class CaptchaImageDataProvider implements StageInterface
{
    public function importData(): array
    {
        echo "importing data\n";
        $parentPath = '../../data/captcha';
        $images = [];

        $list = file_get_contents($parentPath . '/' . 'captcha_data.json');
        $listDecoded = json_decode($list, true);

        foreach ($listDecoded as $single) {
            $images[$single['file_name']] = $single['text'];
            if (count($images) >= 7000) {
                break;
            }
        }

        return $images;
    }

    /**
     * @param CNNPayload $payload
     */
    public function __invoke($payload)
    {
        $images =  $this->importData();
        $payload->setImportedData($images);
        return $payload;
    }
}
