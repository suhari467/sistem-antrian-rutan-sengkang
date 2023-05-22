<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purpose extends Model
{
    use HasFactory;

    protected $guarded = [ 'id' ];

    public function loket()
    {
        return $this->hasMany(Loket::class);
    }

    public function queue()
    {
        return $this->hasMany(Queue::class);
    }

    public function action()
    {
        return $this->hasMany(Action::class);
    }
}
