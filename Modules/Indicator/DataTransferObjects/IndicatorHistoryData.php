<?php

namespace Modules\Indicator\DataTransferObjects;

use Spatie\LaravelData\Data;

class IndicatorHistoryData extends Data
{
  public function __construct(  
    public ?string $original_indicator_name,
    public ?string $indicator_id,
    public ?string $original_norm_text,
    public ?int $norm_flag,
    public string $result,
    public ?string $validated_at,
    public ?string $validated_who,
    public ?string $original_measure_type,
    public ?int $measure_type_id,
    public ?array $probably_indicators,
    public ?array $probably_measure_types
  ) {
  }
}
