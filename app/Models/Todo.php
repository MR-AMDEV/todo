<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'task',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime'
    ];

    public function deadline(): Attribute
    {
        $user = Auth::user();

        // While Getting - Convert UTC stored datetime to local timezone
        // While Setting - Parse datetime in local timezone and then convert to UTC
        return new Attribute(
            get: fn($value) => Carbon::parse($value)->timezone($user->timezone)->format('Y-m-d\TH:i'),
            set: fn($value) => Carbon::parse($value, $user->timezone ?? 'UTC')->timezone(config('app.timezone'))
        );
    }
}
