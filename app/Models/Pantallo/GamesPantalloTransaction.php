<?php

namespace App\Models\Pantallo;

use Illuminate\Database\Eloquent\Model;

class GamesPantalloTransaction extends Model
{
    protected $table = 'games_pantallo_transactions';
    protected $fillable = ['system_id', 'transaction_id', 'balance'];
}
