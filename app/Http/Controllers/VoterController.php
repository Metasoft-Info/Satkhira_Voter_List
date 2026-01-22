<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use App\Models\SearchType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoterController extends Controller
{
    public function index()
    {
        // Load search types
        $searchTypes = SearchType::orderBy('order')->get();
        
        // Get unique upazilas from actual voter data
        $upazilas = Voter::select('upazila')
                        ->distinct()
                        ->whereNotNull('upazila')
                        ->orderBy('upazila')
                        ->pluck('upazila');
        
        return view('voters.index', compact('searchTypes', 'upazilas'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'search_type' => 'required|exists:search_types,id',
            'search_value' => 'required|string',
        ]);

        // Get the search type
        $searchType = SearchType::find($request->search_type);
        
        $query = Voter::query();

        // Apply search based on type
        switch ($searchType->name_en) {
            case 'Name':
                $query->where('name', 'LIKE', "%{$request->search_value}%");
                break;
            case 'Voter ID':
                $query->where('voter_id', 'LIKE', "%{$request->search_value}%");
                break;
            case 'Date of Birth':
                $query->where('date_of_birth', 'LIKE', "%{$request->search_value}%");
                break;
            case 'Father Name':
                $query->where('father_name', 'LIKE', "%{$request->search_value}%");
                break;
            case 'Mother Name':
                $query->where('mother_name', 'LIKE', "%{$request->search_value}%");
                break;
            case 'Occupation':
                $query->where('occupation', 'LIKE', "%{$request->search_value}%");
                break;
        }

        // Apply optional filters from actual voter data
        if ($request->filled('upazila') && $request->upazila !== '') {
            $query->where('upazila', $request->upazila);
        }

        if ($request->filled('union') && $request->union !== '') {
            $query->where('union', $request->union);
        }

        if ($request->filled('ward') && $request->ward !== '') {
            $query->where('ward', $request->ward);
        }

        if ($request->filled('area_code') && $request->area_code !== '') {
            $query->where('area_code', $request->area_code);
        }

        if ($request->filled('center') && $request->center !== '') {
            $query->where('center_name', $request->center);
        }

        // Get results (limit to prevent performance issues)
        $results = $query->limit(500)->get();

        return response()->json($results);
    }

    public function getUnions($upazila)
    {
        // Get unique unions for the selected upazila from actual voter data
        $unions = Voter::select('union')
                      ->where('upazila', $upazila)
                      ->distinct()
                      ->whereNotNull('union')
                      ->orderBy('union')
                      ->pluck('union');
        
        return response()->json($unions);
    }

    public function getWards($upazila, $union)
    {
        // Get unique wards for the selected upazila and union from actual voter data
        $wards = Voter::select('ward')
                     ->where('upazila', $upazila)
                     ->where('union', $union)
                     ->distinct()
                     ->whereNotNull('ward')
                     ->orderBy('ward')
                     ->pluck('ward');
        
        return response()->json($wards);
    }

    public function getAreaCodes($upazila, $union, $ward)
    {
        // Get unique area codes for the selected filters from actual voter data
        $areaCodes = Voter::select('area_code', 'area_name')
                         ->where('upazila', $upazila)
                         ->where('union', $union)
                         ->where('ward', $ward)
                         ->distinct()
                         ->whereNotNull('area_code')
                         ->orderBy('area_code')
                         ->get();
        
        return response()->json($areaCodes);
    }

    public function getCenters($upazila, $union, $ward, $areaCode)
    {
        // Get unique centers for the selected filters from actual voter data
        $centers = Voter::select('center_name', 'center_no')
                       ->where('upazila', $upazila)
                       ->where('union', $union)
                       ->where('ward', $ward)
                       ->where('area_code', $areaCode)
                       ->distinct()
                       ->whereNotNull('center_name')
                       ->orderBy('center_no')
                       ->get();
        
        return response()->json($centers);
    }
}
