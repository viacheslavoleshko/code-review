<?php

namespace Modules\Terralab\Entities;

use App\Traits\HasActiveLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Indicator\Entities\IndicatorLaboratory;

class OrderTest extends Model
{
    use HasFactory, HasActiveLog;
    use \App\Traits\UsesRandomId;

    protected $table = 'order_tests';
    protected $fillable = ['order_laboratory_id', 'indicator_id', 'name', 'material', 'ready', 'pdf'];
    public $timestamps = false;

    public function patientLaboratoryOrder()
    {
        return $this->belongsTo(PatientLaboratoryOrder::class, 'order_laboratory_id');
    }

    public function orderTestResults()
    {
        return $this->hasMany(OrderTestResult::class);
    }

    public function orderTestOriginalResults()
    {
        return $this->hasMany(OrderTestOriginalResult::class);
    }

    protected static function newFactory()
    {
        return \Modules\Terralab\Database\factories\OrderTestFactory::new();
    }
}
