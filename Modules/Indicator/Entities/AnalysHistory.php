<?php

namespace Modules\Indicator\Entities;

use App\Models\User;
use App\Traits\HasActiveLog;
use Illuminate\Database\Eloquent\Model;
use Modules\Directory\Entities\CatalogAnalys;
use Modules\Directory\Entities\CatalogLaboratory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnalysHistory extends Model
{
    use HasFactory, HasActiveLog;

    protected $table = 'analysis_history';
    protected $fillable = [
        'user_id', 'laboratory_id', 'date',
        'original_analys_name', 'analys_id', 'src_type',
        'probably_analysis'
    ];

    protected $casts = [
        'probably_analysis' => 'array'
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function laboratory()
    {
        return $this->belongsTo(CatalogLaboratory::class, 'laboratory_id', 'id');
    }

    public function analys()
    {
        return $this->belongsTo(CatalogAnalys::class);
    }

    public function indicators()
    {
        return $this->hasMany(IndicatorHistory::class, 'history_id', 'id');
    }

    public function probablyAnalysis()
    {
        return $this->belongsToJson(CatalogAnalys::class, 'probably_analysis', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Indicator\Database\factories\AnalysHistoryFactory::new();
    }
}
