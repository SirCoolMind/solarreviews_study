<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Address;


class Lead extends Model
{
    use HasFactory,SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $fillable = [
    'first_name', 'last_name', 'phone','electric_bill'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
    'created_at', 'updated_at',
    ];


    /**
     * Get the address associated with the lead.
     */

    public function address()
    {
        return $this->hasOne(Address::class)->withTrashed();
    }
}
