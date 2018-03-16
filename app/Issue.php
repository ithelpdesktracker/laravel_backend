<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    //
    protected $table = 'tickets';
    protected $primaryKey = 'iss_id';
    
}
