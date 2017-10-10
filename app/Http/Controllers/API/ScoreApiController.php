<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Score;
use Carbon\Carbon;

class ScoreApiController extends Controller
{
  /**
   * API endpoint to retrieve top 5 scores
   * @param  Request $request
   * @return Collection returns the top 5 scores from DB
   */
  public function fetchScores(Request $request)
  {
    // We'll limit it to the top 5 scores by default
    $scores = \App\Score::orderBy('score', 'DESC')
    ->limit(5)
    ->get();
    return $scores;
  }

  /**
   * API endpoint to store a new score
   * @param  Request $request
   * @return Collection returns the top 5 scores from DB
   */
  public function storeScore(Request $request)
  {
    // Add the new score
    \App\Score::create([
      'name' => $request->name,
      'score' => $request->score,
      'score_date' => Carbon::now()
    ]);
    // return top 5 scores for the Vue data
    $scores = \App\Score::orderBy('score', 'DESC')
    ->limit(5)
    ->get();
    return $scores;
  }

}
