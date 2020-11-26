<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [ 'first_name', 'last_name' ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function makeAdmin()    
    {
        if ( ! $this->user->hasRole('admin') ) {
            $adminRole = Role::where('description','admin')->first();
            $this->user->roles()->attach($adminRole);
            $this->user->load('roles');
        }
    }

    public function revokeAdminPrivileges()    
    {
        if ( $this->user->hasRole('admin') ) {
            foreach ($this->user->roles as $role) {
                if ($role->description === 'admin') {
                    $this->user->roles()->detach($role);
                    break;
                }
            }
            $this->user->load('roles');
        }
    }
}
