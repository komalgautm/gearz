<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffLogEvent extends Model
{
    use HasFactory;
    // Mass assignable attributes
    protected $fillable = ['staff_id', 'logDate', 'event_name','event_category','event_id'];

    public static function createStaffLogEvent(array $data)
    {
        //print_r($data);
        return self::create($data);
    }
}
