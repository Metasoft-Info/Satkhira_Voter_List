<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use App\Models\Banner;
use App\Models\BreakingNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Get database-agnostic string concatenation
     * SQLite uses || operator, MySQL uses CONCAT()
     */
    private function concatFields(...$fields): string
    {
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            return implode(" || ", $fields);
        }
        
        // MySQL, PostgreSQL, etc.
        return "CONCAT(" . implode(", ", $fields) . ")";
    }

    public function index()
    {
        $banners = Banner::active()->orderBy('order')->get();
        $breakingNews = BreakingNews::active()->orderBy('order')->get();
        
        $totalVoters = Voter::count();
        $maleVoters = Voter::where('gender', 'পুরুষ')->count();
        $femaleVoters = Voter::where('gender', 'মহলিা')->count();
        $centerCount = Voter::distinct('center_name')->count('center_name');
        
        // Get upazilas with counts
        $upazilas = Voter::select('upazila as name', DB::raw('count(*) as count'))
            ->whereNotNull('upazila')
            ->where('upazila', '!=', '')
            ->groupBy('upazila')
            ->orderBy('upazila')
            ->get()
            ->toArray();
        
        return view('home', compact(
            'banners', 
            'breakingNews', 
            'totalVoters', 
            'maleVoters', 
            'femaleVoters', 
            'centerCount',
            'upazilas'
        ));
    }
    
    // Convert English numbers to Bengali
    private function englishToBengali($string)
    {
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $bengali = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str_replace($english, $bengali, $string);
    }
    
    // Convert Bengali numbers to English
    private function bengaliToEnglish($string)
    {
        $bengali = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($bengali, $english, $string);
    }
    
    // Check if string contains only numbers (English or Bengali)
    private function isNumeric($string)
    {
        $cleaned = $this->bengaliToEnglish($string);
        return preg_match('/^[0-9]+$/', $cleaned);
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
        $searchType = $request->input('search_type');
        $upazila = $request->input('upazila');
        $union = $request->input('union');
        $ward = $request->input('ward');
        $areaCode = $request->input('area_code');
        $center = $request->input('center');
        $gender = $request->input('gender');
        
        $voterQuery = Voter::query();
        
        // Auto-detect search type if query is provided but no specific type
        if ($query && !$searchType) {
            // Check if it's a number (voter ID or serial)
            if ($this->isNumeric($query)) {
                $queryEnglish = $this->bengaliToEnglish($query);
                
                // If length is 10+, it's likely a voter ID
                if (strlen($queryEnglish) >= 10) {
                    $searchType = 'voter_id';
                } else {
                    $searchType = 'serial_no';
                }
            } else {
                // Text query - default to name search
                $searchType = 'name';
            }
        }
        
        // Apply search by type
        if ($searchType && $query) {
            // Convert numbers to Bengali for database search
            $queryBengali = $this->englishToBengali($query);
            $queryEnglish = $this->bengaliToEnglish($query);
            
            switch ($searchType) {
                case 'voter_id':
                    // Search with both Bengali and original query
                    $voterQuery->where(function($q) use ($query, $queryBengali) {
                        $q->where('voter_id', 'LIKE', "%{$queryBengali}%")
                          ->orWhere('voter_id', 'LIKE', "%{$query}%");
                    });
                    break;
                case 'name':
                    $voterQuery->where('name', 'LIKE', "%{$query}%");
                    break;
                case 'father_name':
                    $voterQuery->where('father_name', 'LIKE', "%{$query}%");
                    break;
                case 'mother_name':
                    $voterQuery->where('mother_name', 'LIKE', "%{$query}%");
                    break;
                case 'serial_no':
                    // Search with both Bengali and English numbers
                    $voterQuery->where(function($q) use ($query, $queryBengali, $queryEnglish) {
                        $q->where('serial_no', 'LIKE', "%{$queryBengali}%")
                          ->orWhere('serial_no', 'LIKE', "%{$queryEnglish}%")
                          ->orWhere('serial_no', 'LIKE', "%{$query}%");
                    });
                    break;
            }
        }
        
        // Apply filters
        if ($upazila) {
            $voterQuery->where('upazila', $upazila);
        }
        if ($union) {
            $voterQuery->where('union', $union);
        }
        if ($ward) {
            $voterQuery->where('ward', $ward);
        }
        if ($areaCode) {
            $voterQuery->where('area_code', $areaCode);
        }
        if ($center) {
            $voterQuery->where('center_name', $center);
        }
        if ($gender) {
            $voterQuery->where('gender', $gender);
        }
        
        $voters = $voterQuery->orderBy('serial_no')->paginate(20)->withQueryString();
        
        $banners = Banner::active()->orderBy('order')->get();
        $breakingNews = BreakingNews::active()->orderBy('order')->get();
        
        return view('search-results', compact(
            'voters', 
            'query', 
            'searchType', 
            'banners', 
            'breakingNews'
        ));
    }
    
    // API endpoints for dynamic filters
    public function getUnions(Request $request)
    {
        $upazila = $request->input('upazila');
        
        $unions = Voter::select('union as name', DB::raw('count(*) as count'))
            ->when($upazila, fn($q) => $q->where('upazila', $upazila))
            ->whereNotNull('union')
            ->where('union', '!=', '')
            ->groupBy('union')
            ->orderBy('union')
            ->get();
        
        return response()->json($unions);
    }
    
    public function getWards(Request $request)
    {
        $upazila = $request->input('upazila');
        $union = $request->input('union');
        
        $wards = Voter::select('ward as name', DB::raw('count(*) as count'))
            ->when($upazila, fn($q) => $q->where('upazila', $upazila))
            ->when($union, fn($q) => $q->where('union', $union))
            ->whereNotNull('ward')
            ->where('ward', '!=', '')
            ->groupBy('ward')
            ->orderBy('ward')
            ->get();
        
        return response()->json($wards);
    }
    
    public function getAreaCodes(Request $request)
    {
        $upazila = $request->input('upazila');
        $union = $request->input('union');
        $ward = $request->input('ward');
        
        $concat = $this->concatFields("area_code", "' - '", "area_name");
        
        $areaCodes = Voter::select(
                DB::raw("{$concat} as name"),
                'area_code',
                DB::raw('count(*) as count')
            )
            ->when($upazila, fn($q) => $q->where('upazila', $upazila))
            ->when($union, fn($q) => $q->where('union', $union))
            ->when($ward, fn($q) => $q->where('ward', $ward))
            ->whereNotNull('area_code')
            ->where('area_code', '!=', '')
            ->groupBy('area_code', 'area_name')
            ->orderBy('area_code')
            ->get();
        
        return response()->json($areaCodes);
    }
    
    public function getCenters(Request $request)
    {
        $upazila = $request->input('upazila');
        $union = $request->input('union');
        $ward = $request->input('ward');
        $areaCode = $request->input('area_code');
        
        $concat = $this->concatFields("center_no", "' - '", "center_name");
        
        $query = Voter::select(
                DB::raw("{$concat} as name"),
                'center_name',
                DB::raw('count(*) as count')
            )
            ->when($upazila, fn($q) => $q->where('upazila', $upazila))
            ->when($union, fn($q) => $q->where('union', $union))
            ->when($ward, fn($q) => $q->where('ward', $ward))
            ->when($areaCode, fn($q) => $q->where('area_code', $areaCode))
            ->whereNotNull('center_name')
            ->groupBy('center_no', 'center_name')
            ->orderBy('center_no');
        
        return response()->json($query->get());
    }
    
    public function getFilterCount(Request $request)
    {
        $query = Voter::query();
        
        if ($request->upazila) {
            $query->where('upazila', $request->upazila);
        }
        if ($request->union) {
            $query->where('union', $request->union);
        }
        if ($request->ward) {
            $query->where('ward', $request->ward);
        }
        if ($request->area_code) {
            $query->where('area_code', $request->area_code);
        }
        if ($request->center) {
            $query->where('center_name', $request->center);
        }
        if ($request->gender) {
            $query->where('gender', $request->gender);
        }
        
        return response()->json(['count' => $query->count()]);
    }

    /**
     * Get all voters for offline caching (paginated for large datasets)
     */
    public function getAllVotersForCache(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 1000;
        
        $voters = Voter::select([
                'id', 'serial_no', 'name', 'voter_id', 'father_name', 'mother_name',
                'date_of_birth', 'gender', 'upazila', 'union', 'ward', 
                'area_code', 'area_name', 'center_name', 'address', 'occupation'
            ])
            ->orderBy('id')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $total = Voter::count();
        $hasMore = ($page * $perPage) < $total;

        return response()->json([
            'voters' => $voters,
            'page' => $page,
            'total' => $total,
            'hasMore' => $hasMore,
            'perPage' => $perPage
        ]);
    }

    /**
     * Get sync data (last update time and count)
     */
    public function getVotersSyncData()
    {
        $lastVoter = Voter::latest('updated_at')->first();
        
        return response()->json([
            'total' => Voter::count(),
            'lastUpdate' => $lastVoter ? $lastVoter->updated_at->toISOString() : null,
            'checksum' => md5(Voter::count() . ($lastVoter ? $lastVoter->updated_at : ''))
        ]);
    }
}
