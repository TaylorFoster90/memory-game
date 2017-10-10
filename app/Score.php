<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
  // Fillable Model fields
  protected $fillable = [
    'name',
    'score',
    'score_date',
  ];
  // No standard timestamps on this model
  public $timestamps = false;

  // Carbon dates
  protected $dates = [
    'score_date',
  ];
}
