<?php

namespace Modules\Terralab\Entities;

use App\Traits\HasActiveLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderTestResult extends Model
{
    use HasFactory, HasActiveLog;

    protected $table = 'order_test_results';
    protected $fillable = [
        'order_test_id',
        'material_name',
        'test_name',
        'test_code',
        'gis',
        'indicator_name',
        'unit_name',
        'result',
        'norm_text',
        'ubnormal_flag',
        'payload',
        'order',
        'date_ready',
        'done_employee_name',
        'done_employee_id',
        'done_employee_post'
    ];

    public function orderTest()
    {
        return $this->belongsTo(OrderTest::class);
    }

    protected static function newFactory()
    {
        return \Modules\Terralab\Database\factories\OrderTestResultFactory::new();
    }
}
