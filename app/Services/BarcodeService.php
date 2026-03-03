<?php

namespace App\Services;

use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeService
{
    public function generateHTML($barcode, $type = 'TYPE_CODE_128')
    {
        $generator = new BarcodeGeneratorHTML();
        return $generator->getBarcode($barcode, constant("Picqer\Barcode\BarcodeGenerator::$type"));
    }

    public function generatePNG($barcode, $type = 'TYPE_CODE_128')
    {
        $generator = new BarcodeGeneratorPNG();
        return base64_encode($generator->getBarcode($barcode, constant("Picqer\Barcode\BarcodeGenerator::$type")));
    }
}
