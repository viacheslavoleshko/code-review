<?php

namespace Modules\Indicator\DataTransferObjects;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ManualInputAnalysHistoryData extends Data
{
    public function __construct(
      public int $user_id,
      public int $laboratory_id,       
      public string $date,      
      public int $analys_id,     
      /** @var \Modules\Indicator\DataTransferObjects\ManualInputIndicatorHistoryData[] */
      public DataCollection $indicators
    ) {}
}
