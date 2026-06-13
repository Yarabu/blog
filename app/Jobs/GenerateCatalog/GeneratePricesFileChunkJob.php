<?php
namespace App\Jobs\GenerateCatalog;

class GeneratePricesFileChunkJob extends AbstractJob
{
    public function __construct($chunk, $fileNum)
    {
        parent::__construct();
        // Приймаємо параметри, інакше буде помилка PHP
    }
}
