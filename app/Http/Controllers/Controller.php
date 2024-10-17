<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function encryptId($id) {
        $salt = "chuoi_noi_voi_id";
        return base64_encode($id . $salt);
    }

    public function decryptId($encryptedId) {
        $salt = "chuoi_noi_voi_id";
        $decoded = base64_decode($encryptedId);
        return str_replace($salt, '', $decoded);
    }
}
