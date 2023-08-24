<?php

namespace Modules\Terralab\Entities;

use App\Traits\HasActiveLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderTestOriginalResult extends Model
{
    use HasFactory, HasActiveLog;

    protected $table = 'order_test_original_results';
    protected $fillable = ['order_test_original_id', 'uri'];

    public function orderTest()
    {
        return $this->belongsTo(OrderTest::class);
    }

    protected static function newFactory()
    {
        return \Modules\Terralab\Database\factories\OrderTestOriginalResultFactory::new();
    }
}
