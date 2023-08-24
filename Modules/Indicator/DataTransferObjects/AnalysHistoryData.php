<?php

namespace Modules\Indicator\DataTransferObjects;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class AnalysHistoryData extends Data
{
    public function __construct(
      public int $user_id,
      public int $laboratory_id,       
      public string $date,
      public ?string $original_analys_name,
      public ?int $analys_id,
      public string $src_type,
      public ?array $probably_analysis,
      /** @var \Modules\Indicator\DataTransferObjects\IndicatorHistoryData[] */
      public DataCollection $indicators
    ) {}
}
