<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use App\Models\SearchType;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Ward;
use App\Models\AreaCode;
use App\Models\VoteCenter;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    public function index()
    {
        // Load all dropdown data for the public search page
        $searchTypes = SearchType::orderBy('order')->get();
        $upazilas = Upazila::all();
        $wards = Ward::all();
        
        return view('voters.index', compact('searchTypes', 'upazilas', 'wards'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'search_type' => 'required|exists:search_types,id',
            'search_value' => 'required|string',
        ]);

        // Get the search type to determine which column to search
        $searchType = SearchType::find($request->search_type);
        
        $query = Voter::query();

        // Apply search based on type
        switch ($searchType->name_en) {
            case 'Name':
                $query->where('name_bn', 'LIKE', "%{$request->search_value}%");
                break;
            case 'Voter ID':
                $query->where('voter_id', 'LIKE', "%{$request->search_value}%");
                break;
            case 'Date of Birth':
                $query->where('date_of_birth', 'LIKE', "%{$request->search_value}%");
                break;
            case 'Father Name':
                $query->where('father_name_bn', 'LIKE', "%{$request->search_value}%");
                break;
            case 'Mother Name':
                $query->where('mother_name_bn', 'LIKE', "%{$request->search_value}%");
                break;
            case 'Occupation':
                $query->where('occupation_bn', 'LIKE', "%{$request->search_value}%");
                break;
        }

        // Apply optional filters
        if ($request->filled('upazila')) {
            $upazila = Upazila::find($request->upazila);
            if ($upazila) {
                $query->where('upazila', $upazila->name_bn);
            }
        }

        if ($request->filled('union')) {
            $union = Union::find($request->union);
            if ($union) {
                $query->where('union_ward', $union->name_bn);
            }
        }

        if ($request->filled('ward')) {
            $ward = Ward::find($request->ward);
            if ($ward) {
                $query->where('union_ward', 'LIKE', "%{$ward->name_bn}%");
            }
        }

        if ($request->filled('area_code')) {
            $areaCode = AreaCode::find($request->area_code);
            if ($areaCode) {
                // Area codes don't directly map to voters table columns
                // This would need a relationship setup or additional logic
                // For now, we'll skip this filter or implement based on your data structure
            }
        }

        // Get results (limit to prevent performance issues)
        $results = $query->limit(500)->get();

        return response()->json($results);
    }

    public function getUnions($upazilaId)
    {
        $unions = Union::where('upazila_id', $upazilaId)
                      ->orderBy('name_bn')
                      ->get(['id', 'name_bn', 'name_en']);
        
        return response()->json($unions);
    }

    public function getAreaCodes($unionId)
    {
        $areaCodes = AreaCode::where('union_id', $unionId)
                             ->with('voteCenter')
                             ->orderBy('area_code_no')
                             ->get();
        
        // Transform to include vote center names
        $areaCodes = $areaCodes->map(function($areaCode) {
            return [
                'id' => $areaCode->id,
                'area_code_no' => $areaCode->area_code_no,
                'vote_center_name_bn' => $areaCode->voteCenter ? $areaCode->voteCenter->name_bn : '',
                'vote_center_name_en' => $areaCode->voteCenter ? $areaCode->voteCenter->name_en : '',
            ];
        });
        
        return response()->json($areaCodes);
    }
}
