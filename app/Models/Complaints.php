<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Complaints extends Model
{
    use HasFactory;

    public function user()
    {
        // dd('coming here');
        return $this->belongsTo(User::class);

    }
}
