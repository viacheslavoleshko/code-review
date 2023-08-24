<?php

namespace Modules\Indicator\Entities;

use App\Traits\HasActiveLog;
use Illuminate\Database\Eloquent\Model;
use Modules\Directory\Entities\CatalogAnalys;
use Modules\Directory\Entities\CatalogIndicator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnalysGroup extends Model
{
    use HasFactory, HasActiveLog;

    protected $table = 'analysis_groups';
    protected $fillable = ['indicator_id', 'analys_id'];

    public function indicator()
    {
        return $this->belongsTo(CatalogIndicator::class);
    }

    public function analys()
    {
        return $this->belongsTo(CatalogAnalys::class);
    }

    protected static function newFactory()
    {
        return \Modules\Indicator\Database\factories\AnalysGroupFactory::new();
    }
}
