<?php

namespace Modules\Terralab\Entities;

use App\Traits\HasActiveLog;
use Illuminate\Database\Eloquent\Model;
use Modules\Directory\Entities\CatalogServicePackage;

class PackageAnalysis extends Model
{
    use HasActiveLog;

    protected $table = 'package_analyses';
    protected $fillable = ['catalog_service_package_id', 'analysis'];
    protected $casts = [
        'analysis' => 'array'
    ];
    const GENDER_TYPE = [
        '30637' => 'male',
        '30636' => 'female',
        '30305' => 'male'
    ];

    public function catalogServicePackage()
    {
        return $this->hasOne(CatalogServicePackage::class);
    }
}
