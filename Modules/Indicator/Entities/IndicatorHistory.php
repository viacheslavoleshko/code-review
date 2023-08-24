<?php

namespace Modules\Indicator\Entities;

use App\Models\User;
use App\Traits\HasActiveLog;
use Illuminate\Database\Eloquent\Model;
use Modules\Directory\Entities\CatalogIndicator;
use Modules\Directory\Entities\CatalogMeasureType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IndicatorHistory extends Model
{
    use HasFactory, HasActiveLog;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $table = 'indicator_history';
    protected $fillable = [
        'history_id', 'original_indicator_name', 'indicator_id', 'original_norm_text', 'norm_flag',
        'result', 'validated_at', 'validated_who', 'original_measure_type', 'measure_type_id',
        'probably_indicators', 'probably_measure_types'
    ];

    protected $casts = [
        'probably_indicators' => 'array',
        'probably_measure_types' => 'array',
    ];

    public function getSlug()
    {
        if ($this->indicator_id !== null) {
            return $this->catalogIndicator->slug;
        }
        return normalizeSlug($this->original_indicator_name);
    }

    public function getName()
    {
        if ($this->indicator_id !== null and isset($this->catalogIndicator)) {
            return $this->catalogIndicator->name;
        }
        return $this->original_indicator_name;
    }

    public function getNormText()
    {
        if (!$this->original_norm_text and $this->indicator_id !== null and isset($this->catalogIndicator)) {
            return $this->catalogIndicator->etalon_norm_text;
        }
        return $this->original_norm_text;
    }

    public function getMeasureType()
    {
        if ($this->measure_type_id !== null and isset($this->measureType)) {
            return $this->measureType->name;
        } elseif (!$this->original_measure_type and $this->indicator_id !== null and isset($this->catalogIndicator->etalonMeasureType)) {
            return $this->catalogIndicator->etalonMeasureType->name;
        }
        return $this->original_measure_type;
    }

    public function getMeasureTypeSlug()
    {
        if ($this->measure_type_id !== null) {
            return $this->measureType->getTranslation('name', 'en');
        } elseif (!$this->original_measure_type && $this->indicator_id !== null && isset($this->catalogIndicator->etalonMeasureType)) {
            return $this->catalogIndicator->etalonMeasureType->getTranslation('name', 'en') ?? '';
        } elseif ($this->original_measure_type) {
            return normalizeSlug($this->original_measure_type);
        }
        return '';
    }

    public function isConfirmed()
    {
        if ($this->indicator_id !== null) {
            return true;
        }
        return false;
    }

    public function isValidated()
    {
        if ($this->indicator_id !== null and $this->validated_at !== null) {
            return true;
        }
        return false;
    }

    public function analys()
    {
        return $this->belongsTo(AnalysHistory::class);
    }

    public function catalogIndicator()
    {
        return $this->belongsTo(CatalogIndicator::class, 'indicator_id', 'id');
    }

    public function measureType()
    {
        return $this->belongsTo(CatalogMeasureType::class);
    }

    public function validatedWho()
    {
        return $this->belongsTo(User::class);
    }

    public function probablyIndicators()
    {
        return $this->belongsToJson(CatalogIndicator::class, 'probably_indicators', 'id');
    }

    public function probablyMeasureTypes()
    {
        return $this->belongsToJson(CatalogMeasureType::class, 'probably_measure_types', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Indicator\Database\factories\IndicatorHistoryFactory::new();
    }
}
