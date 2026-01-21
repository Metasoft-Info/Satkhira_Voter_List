<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use App\Models\SearchType;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Ward;
use App\Models\AreaCode;
use App\Models\VoteCenter;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_voters' => Voter::count(),
            'search_types' => SearchType::count(),
            'upazilas' => Upazila::count(),
            'unions' => Union::count(),
            'wards' => Ward::count(),
            'area_codes' => AreaCode::count(),
            'vote_centers' => VoteCenter::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function voters()
    {
        $voters = Voter::orderBy('serial_no')->paginate(100);
        
        return view('admin.voters', compact('voters'));
    }

    public function upload()
    {
        $lastUpload = Voter::latest('updated_at')->first();
        
        return view('admin.upload', compact('lastUpload'));
    }

    public function dropdowns()
    {
        $searchTypes = SearchType::orderBy('order')->get();
        $upazilas = Upazila::with('unions')->get();
        $unions = Union::with('upazila')->get();
        $wards = Ward::all();
        $areaCodes = AreaCode::with(['upazila', 'union', 'ward'])->get();
        $voteCenters = VoteCenter::all();

        return view('admin.dropdowns', compact(
            'searchTypes',
            'upazilas',
            'unions',
            'wards',
            'areaCodes',
            'voteCenters'
        ));
    }
}
