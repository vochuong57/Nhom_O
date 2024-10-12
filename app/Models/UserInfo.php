<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;

class UserInfo extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'birthday',
        'image',
        'description',
        'user_catalogue_id',
        'user_id'
    ];

    protected $table='user_info';

    protected $primaryKey = 'user_id';


    public function users() {
        return $this->hasOne(User::class);
    }
    
    public function user_catalogues() {
        return $this->belongsTo(UserCatalogue::class);
    }
    
}
