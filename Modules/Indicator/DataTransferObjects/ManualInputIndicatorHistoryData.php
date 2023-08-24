<?php

namespace Modules\Indicator\DataTransferObjects;

use Spatie\LaravelData\Data;

class ManualInputIndicatorHistoryData extends Data
{
  public function __construct(
    public int $indicator_id,
    public ?string $original_norm_text,
    public ?int $norm_flag,
    public string $result,
    public ?int $measure_type_id
  ) {
  }
}
