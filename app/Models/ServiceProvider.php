<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',           // Add 'name' here
        'name_ar',        // Add 'name_ar' here
        'logo',          // Add 'logo' if needed
        'description',   // Add 'description' if needed
        'description_ar',          // Add 'description_ar' if needed
        'email',          // Add 'image' if needed
        'contact',          // Add 'image' if needed
        'website',          // Add 'image' if needed
        'youtube',          // Add 'image' if needed
        'twitter',          // Add 'image' if needed
        'linkedin',          // Add 'image' if needed
        'facebook',          // Add 'image' if needed
        'instagram',          // Add 'image' if needed
        'snap_chat',          // Add 'image' if needed
        'tag_icon',          // Add 'image' if needed
        'tag_name',          // Add 'image' if needed
        'tag_name_ar',          // Add 'image' if needed
        'status',          // Add 'image' if needed
        'position',          // Add 'image' if needed
        'log',          // Add 'image' if needed
        // Add other attributes you want to allow mass assignment for
    ];
}
