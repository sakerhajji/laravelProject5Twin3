<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Objective;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProgressAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Progress::query()->with(['user','objective'])->latest('entry_date');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('objective_id')) {
            $query->where('objective_id', $request->objective_id);
        }
        if ($request->filled('date_from')) {
            $query->where('entry_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('entry_date', '<=', $request->date_to);
        }

        $progresses = $query->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get(['id','name']);
        $objectives = Objective::orderBy('title')->get(['id','title']);

        return view('backoffice.progress.index', compact('progresses','users','objectives'));
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Progress::query()->with(['user','objective'])->orderBy('entry_date');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('objective_id')) {
            $query->where('objective_id', $request->objective_id);
        }
        if ($request->filled('date_from')) {
            $query->where('entry_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('entry_date', '<=', $request->date_to);
        }

        $filename = 'progress_export_'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function() use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['user','objective','date','value','note']);
            $query->chunk(500, function($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        optional($row->user)->name,
                        optional($row->objective)->title,
                        $row->entry_date?->format('Y-m-d'),
                        $row->value,
                        $row->note,
                    ]);
                }
            });
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function destroy(Progress $progress)
    {
        $progress->delete();
        return back()->with('status', 'Progrès supprimé');
    }
}
