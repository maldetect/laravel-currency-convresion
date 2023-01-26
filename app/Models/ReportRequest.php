<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportRequest extends Model
{
    use HasFactory;

    //types
    const ONE_YEAR = "One Year, Interval: Monthly";
    const SIX_MONTH = "Six Months, Interval: Weekly";
    const ONE_MONTH = "One Month, Interval: Daily";

    //status
    const PENDING = 'Pending';
    const READY = 'Ready';
    const ERROR = 'Error';

    protected $fillable = [
        'user_id',
        'currencies',
        'amount',
        'type',
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function report(){
        return $this->hasMany(Report::class);
    }
}
