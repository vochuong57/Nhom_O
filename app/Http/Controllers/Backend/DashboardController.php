<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct(){
        
    }

    public function index(){
        $config=$this->config();
        $template='Backend.home.index';
        return view('Backend.dashboard.layout', compact('template','config'));
    }

    private function config(){
        return [
            'js'=>[
                'Backend/vendor/jquery/jquery.min.js',
                'Backend/vendor/bootstrap/js/bootstrap.bundle.min.js',
                'Backend/vendor/jquery-easing/jquery.easing.min.js',
                'Backend/js/sb-admin-2.min.js',
                'Backend/vendor/chart.js/Chart.min.js',
                'Backend/js/demo/chart-area-demo.js',
                'Backend/js/demo/chart-pie-demo.js',
            ],
            'css'=>[
                'Backend/vendor/fontawesome-free/css/all.min.css',
                'https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i',
                'Backend/css/sb-admin-2.min.css',
            ],
        ];
    }
    
}
