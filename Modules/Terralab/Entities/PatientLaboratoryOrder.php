<?php

namespace Modules\Terralab\Entities;

use App\Models\User;
use App\Traits\HasActiveLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Directory\Entities\CatalogLaboratory;
use Modules\Terralab\Entities\OrderTest;

class PatientLaboratoryOrder extends Model
{
    use HasFactory, HasActiveLog;
    use \App\Traits\UsesUuid;

    protected $table = 'patient_laboratory_orders';
    protected $fillable = ['patient_id', 'laboratory_id', 'doctor_id', 'order_number', 'order_status', 'menstrphase', 'descr'];
    const AVAILABLE_STATUSES = [
        'STAGED', 'PROCESSING', 'PART_READY', 'READY', 'CANCELED'
    ];

    const SELECTOR_DATA = [
        'menstrphases' => [
            100 => 'luteal',
            200 => 'ovulation',
            300 => 'follicular',
            400 => 'premenopause',
            500 => 'menopause',
            600 => 'perimenopause'
        ],
    ];

    public static function getAvailableStatuses()
    {
        return self::AVAILABLE_STATUSES;
    }

    public function patient()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class);
    }

    public function laboratory()
    {
        return $this->belongsTo(CatalogLaboratory::class);
    }

    public function orderTests()
    {
        return $this->hasMany(OrderTest::class, 'order_laboratory_id');
    }

    protected static function newFactory()
    {
        return \Modules\Terralab\Database\factories\PatientLaboratoryOrderFactory::new();
    }

    public function staticTransform($id, $selector)
    {
        return $id ? [
            'id' => $id,
            'label' => __('terralab::static.' . self::SELECTOR_DATA[$selector][$id])
        ] : null;
    }

    public static function getStatic()
    {
        foreach (self::SELECTOR_DATA as $selector => $options) {
            foreach ($options as $id => $option) {
                $staticData[$selector][] = [
                    'id' => $id,
                    'label' => __('terralab::static.' . $option)
                ];
            }
        }
        return $staticData ?? [];
    }
}
