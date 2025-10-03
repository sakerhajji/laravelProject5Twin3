<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maladie;
use App\Models\Asymptome;
use Illuminate\Support\Facades\Auth;

class MaladieDiagnoseController extends Controller
{
    public function showForm()
    {
        $asymptomes = Asymptome::all();
        return view('front.maladie.diagnose', compact('asymptomes'));
    }

    public function history()
    {
        $user = Auth::user();
        $maladies = $user ? $user->maladies()->withPivot('created_at')->get() : collect();
        return view('front.maladie.history', compact('maladies'));
    }

    public function matchMaladies(Request $request)
    {
        $inputAsymptomeIds = $request->input('asymptomes', []);
        $asymptomes = Asymptome::all();
        $maladies = Maladie::with('asymptomes')->get();
        $results = [];
        foreach ($maladies as $maladie) {
            $maladieAsymptomeIds = $maladie->asymptomes->pluck('id')->toArray();
            $matchCount = count(array_intersect($inputAsymptomeIds, $maladieAsymptomeIds));
            $total = count($maladieAsymptomeIds);
            $percentage = $total > 0 ? round(($matchCount / $total) * 100) : 0;
            if ($matchCount > 0) {
                $results[] = [
                    'maladie' => $maladie,
                    'percentage' => $percentage
                ];
            }
        }
        usort($results, fn($a, $b) => $b['percentage'] <=> $a['percentage']);
        return view('front.maladie.diagnose', compact('asymptomes', 'results'));
    }

    public function saveMaladie(Request $request)
    {
        $maladieId = $request->input('maladie_id');
        $user = Auth::user();
        if ($maladieId && $user) {
            $user->maladies()->attach($maladieId);
        }
        return redirect()->route('front.maladie.diagnose')->with('success', 'Maladie saved to your history!');
    }
        public function apiMatch(Request $request)
    {
        $inputAsymptomeIds = $request->input('asymptomes', []);
        $maladies = Maladie::with('asymptomes')->get();
        $results = [];
        foreach ($maladies as $maladie) {
            $maladieAsymptomeIds = $maladie->asymptomes->pluck('id')->toArray();
            $matchCount = count(array_intersect($inputAsymptomeIds, $maladieAsymptomeIds));
            $total = count($maladieAsymptomeIds);
            $percentage = $total > 0 ? round(($matchCount / $total) * 100) : 0;
            if ($matchCount > 0) {
                $results[] = [
                    'maladie' => [
                        'id' => $maladie->id,
                        'nom' => $maladie->nom,
                        'description' => $maladie->description ?? ''
                    ],
                    'percentage' => $percentage
                ];
            }
        }
        usort($results, fn($a, $b) => $b['percentage'] <=> $a['percentage']);
        return response()->json(['results' => $results]);
    }
}
