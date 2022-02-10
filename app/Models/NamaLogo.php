<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class NamaLogo extends Model
{
    use HasFactory;

    protected $table = 'nama_logo';
    protected $appends = [
        'picture_formated'
    ];
    protected $fillable = [
        'title',
        'picture',
        'user_id'
    ];

    public function getPictureFormatedAttribute()
    {
        if ($this->attributes['picture']) {
            return showFile($this->attributes['picture']);
        }

        return '';
    }

    public function author()
    {
        return $this->belongsTo(User::class,'user_id', 'uuid');
    }
}
