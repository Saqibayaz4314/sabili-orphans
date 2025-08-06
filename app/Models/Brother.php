<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brother extends Model
{
    protected $fillable = [

        'orphan_id','brother_name','brother_id_number','brother_gender','brother_birth_date','brother_health_status','brother_medical_report'

    ];

    // one to Many with Orphan
    public function orphan(){
        return $this->belongsTo(Orphan::class);
    }
}
