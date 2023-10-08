<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'original_name',
        'path',
        'user_id',
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }


    public function setPathAttribute($value)
    {
        $this->attributes['path'] = str_replace('/public/', '/storage/', $value);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y H:i:s');
    }
}