<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\WardRepositoryInterface as WardRepository;
use App\Repositories\Interfaces\DistrictRepositoryInterface as DistrictRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use Illuminate\Http\Request;


class LocationController extends Controller
{
    protected $wardRepository;
    protected $districtRepository;
    protected $provinceRepository;
    public function __construct(WardRepository $wardRepository,DistrictRepository $districtRepository, ProvinceRepository $provinceRepository){
        $this->wardRepository=$wardRepository;
        $this->districtRepository=$districtRepository;
        $this->provinceRepository=$provinceRepository;
    }

    public function getLocation(Request $request){
        $html='';
        $get=$request->input();

        if($get['target']=='DTdistricts'){
            $ListDistricts=$this->provinceRepository->findById($get['data']['location_id'],['code','name'],['districts']);
            $html=$this->renderHTML($ListDistricts->districts, '[Chọn Quận/Huyện]');
        }else if($get['target']=='DTwards'){
            $ListWards=$this->districtRepository->findById($get['data']['location_id'],['code','name'],['wards']);
            $html=$this->renderHTML($ListWards->wards, '[Chọn Phường/Xã]');
        }

        $response=[
            'html'=>$html
        ];
        return response()->json($response);
    }
    public function renderHTML($ListLocations, $root=''){
        $html="<option value='0'>$root</option>";
        foreach($ListLocations as $location){
            $html.="<option value='$location->code'>$location->name</option>";
        }
        return $html;
    }
}
