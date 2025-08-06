<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [

        'orphan_id' ,'father_death_certificate','wife_ID' ,'sponsor_ID' ,'birth_certificate' ,'personl_image' , 'medical_report'

    ];

     // one to one with orphan
    public function orphan(){
        return $this->belongsTo(Orphan::class);
    }
}
