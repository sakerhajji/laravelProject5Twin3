<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Objective;
use App\Models\Progress;
use App\Models\UserBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProgressImportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $objectives = $user->objectives()->get();
        
        return view('front.progress.import', compact('objectives'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'objective_id' => 'required|exists:objectives,id',
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $objective = Objective::findOrFail($request->objective_id);
        $file = $request->file('csv_file');
        
        try {
            $csvData = $this->parseCSV($file);
            $imported = $this->importProgressData($csvData, $objective->id);
            
            // Vérifier les badges après import
            UserBadge::checkAndAwardBadges(Auth::id(), $objective->id);
            
            return response()->json([
                'success' => true,
                'message' => "Import réussi: {$imported} entrées ajoutées",
                'imported_count' => $imported
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'import: ' . $e->getMessage()
            ], 500);
        }
    }

    private function parseCSV($file)
    {
        $data = [];
        $handle = fopen($file->getPathname(), 'r');
        
        if ($handle === false) {
            throw new \Exception('Impossible de lire le fichier CSV');
        }

        $header = fgetcsv($handle);
        if (!$header || count($header) < 2) {
            throw new \Exception('Format CSV invalide. Colonnes attendues: date, valeur');
        }

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) >= 2) {
                $data[] = [
                    'date' => trim($row[0]),
                    'value' => trim($row[1]),
                    'note' => isset($row[2]) ? trim($row[2]) : null
                ];
            }
        }

        fclose($handle);
        return $data;
    }

    private function importProgressData($data, $objectiveId)
    {
        $imported = 0;
        $userId = Auth::id();

        foreach ($data as $row) {
            // Validation de la date
            $date = \DateTime::createFromFormat('Y-m-d', $row['date']);
            if (!$date) {
                $date = \DateTime::createFromFormat('d/m/Y', $row['date']);
            }
            if (!$date) {
                continue; // Skip invalid dates
            }

            // Validation de la valeur
            $value = floatval(str_replace(',', '.', $row['value']));
            if ($value <= 0) {
                continue; // Skip invalid values
            }

            // Vérifier si l'entrée existe déjà
            $exists = Progress::where('user_id', $userId)
                ->where('objective_id', $objectiveId)
                ->where('entry_date', $date->format('Y-m-d'))
                ->exists();

            if (!$exists) {
                Progress::create([
                    'user_id' => $userId,
                    'objective_id' => $objectiveId,
                    'entry_date' => $date->format('Y-m-d'),
                    'value' => $value,
                    'note' => $row['note']
                ]);
                $imported++;
            }
        }

        return $imported;
    }

    public function downloadTemplate()
    {
        $csv = "date,valeur,note\n";
        $csv .= "2024-01-01,5.5,Commentaire optionnel\n";
        $csv .= "2024-01-02,6.0,\n";
        $csv .= "2024-01-03,5.8,Très bien\n";

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="template_progres.csv"');
    }
}