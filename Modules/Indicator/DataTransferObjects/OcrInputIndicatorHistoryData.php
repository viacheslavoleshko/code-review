<?php

namespace Modules\Indicator\DataTransferObjects;

use Spatie\LaravelData\Data;

class OcrInputIndicatorHistoryData extends Data
{
  public function __construct(
    public string $original_indicator_name,
    public ?string $original_norm_text,
    public ?int $norm_flag,
    public string $result,
    public ?string $original_measure_type
  ) {
  }
}
