<?php

namespace App\Http\Models;
use Eloquent;

class Status extends Eloquent
{
    protected $table = 'Status';
    protected $primaryKey = 'StatusId';
    //protects all attributes against mass assignment
    protected $guarded = array('*');
}