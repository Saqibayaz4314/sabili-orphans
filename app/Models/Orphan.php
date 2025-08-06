<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Orphan extends Model
{
    use Notifiable;

    protected $fillable = [
        'name', 'id_number', 'birth_date', 'orphan_code', 'address', 'gender', 'health_status',
        'deceased_name', 'deceased_id_number', 'death_deceased_date', 'cause_deceased_death', 'father_work', 'nature_father_work', 'nature_work',
        'mother_name', 'mother_id_number', 'mother_birth_date', 'mother_status', 'mother_work', 'nature_mother_work',
        'guardian_name', 'guardian_id_number', 'guardian_relation', 'guardian_anthor_relation',
        'phone', 'phone1', 'email',
        'bank_name', 'bank_account_owner', 'bank_owner_id_number', 'phone_number_linked_bank', 'bank_account_number',
        'wallet_owner', 'wallet_owner_id_number', 'owner_phone_linked_wallet',
        'role' , 'waiting_reason'
    ];


    // one to one with Image
    public function image(){
        return $this->hasOne(Image::class);
    }

    // one to Many with brothers
    public function brothers(){
        return $this->hasMany(Brother::class);
    }


    public function getAgeAttribute()
    {
        return Carbon::parse($this->birth_date)->age;
    }

    public function sponsorships(){
       return $this->hasMany(Sponsorship::class);
    }

    public function activeSponsorship(){
       return $this->hasMany(Sponsorship::class)->where('role' , 'active');
    }




}
