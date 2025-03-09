<?php

namespace App\Http\Controllers;

use App\Models\Leaderboard;

class LeaderboardController extends Controller
{
    public function index() { return Leaderboard::orderBy('total_score', 'desc')->get(); }
}
