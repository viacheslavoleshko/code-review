<?php

namespace Modules\Indicator\DataTransferObjects;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ApiInputAnalysHistoryData extends Data
{
    public function __construct(
      public int $user_id,
      public int $laboratory_id,       
      public string $date,      
      public string $original_analys_name,     
      /** @var \Modules\Indicator\DataTransferObjects\ApiInputIndicatorHistoryData[] */
      public DataCollection $indicators
    ) {}
}
