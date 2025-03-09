<?php

namespace App\Http\Controllers;

use App\Models\Certificate;

class CertificateController extends Controller
{
    public function getCertificates($user_id) { return Certificate::where('user_id', $user_id)->get(); }
}
