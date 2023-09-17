<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'age'];

    public function userPositions()
    {
        return $this->hasMany(UserPosition::class);
    }

    // Add this method to enable cascading delete
    public static function boot()
    {
        parent::boot();

        // When a user is deleted, also delete related user positions
        static::deleting(function ($user) {
            $user->userPositions()->delete();
        });
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'user_positions');
    }

    public function scopeFilters($query, $request)
    {
        if (isset($request['status']) && $request['status'] != -100) {
            $search = $request['status'];
            $query->where('status', $search);
        }

        if (isset($request['daterange'])) {
            $dateRange = explode(' - ', $request['daterange']);
            $query->whereBetween('orders.created_at', [Carbon::parse($dateRange[0])->format('Y-m-d'), Carbon::parse($dateRange[1])->format('Y-m-d')]);
        }

        if (isset($request['user']) && $request['user'] != -100) {
            $query->where('email', '=', $request['user']);
        }
    }
}
