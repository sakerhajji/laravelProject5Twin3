<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Objective extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title','description','unit','target_value','category','cover_url','mode','period'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_objectives')
            ->withPivot(['status'])->withTimestamps();
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    /**
     * Compute progress percentage for a user depending on mode/period.
     */
    public function computeProgressPercent(int $userId, ?string $range = null): int
    {
        $target = (float) $this->target_value;
        if ($target <= 0) { return 0; }

        $query = $this->progresses()->where('user_id', $userId);

        // Periodic scope (current daily/weekly window)
        if ($this->mode === 'periodic') {
            [$start, $end] = $this->currentPeriodBounds();
            $query->whereBetween('entry_date', [$start->toDateString(), $end->toDateString()]);
        } elseif (in_array($range, ['7','30','90'], true)) {
            $query->where('entry_date', '>=', now()->subDays((int) $range)->toDateString());
        }

        $sum = (float) $query->sum('value');

        if ($this->mode === 'inverse') {
            // For inverse mode we expect target is the goal to reach from a starting higher value.
            // If a baseline exists in the first progress, compute from it.
            $first = $this->progresses()->where('user_id', $userId)->oldest('entry_date')->first();
            $current = $this->progresses()->where('user_id', $userId)->latest('entry_date')->value('value') ?? 0.0;
            $start = $first? (float) $first->value : ($current > $target ? $current : $target);
            $den = max(0.0001, $start - $target);
            $pct = (($start - (float) $current) / $den) * 100.0;
            return max(0, min(100, (int) round($pct)));
        }

        // cumulative and periodic behave like cumulative on their window
        $pct = ($sum / $target) * 100.0;
        return max(0, min(100, (int) round($pct)));
    }

    /**
     * Return [start,end] Carbon bounds for the current period window.
     */
    public function currentPeriodBounds(): array
    {
        $today = Carbon::today();
        if ($this->period === 'weekly') {
            return [ (clone $today)->startOfWeek(), (clone $today)->endOfWeek() ];
        }
        return [ $today, $today ]; // daily by default
    }

    /**
     * Aggregate daily series for Chart (labels, values, cumulative) within a range in days.
     */
    public function seriesForUser(int $userId, int $days = 30): array
    {
        $start = now()->subDays($days-1)->startOfDay();
        $vals = $this->progresses()
            ->where('user_id', $userId)
            ->where('entry_date', '>=', $start->toDateString())
            ->orderBy('entry_date')
            ->get(['entry_date','value'])
            ->groupBy('entry_date')
            ->map(fn(Collection $c) => (float) $c->sum('value'));

        $labels = [];
        $daily = [];
        $cumulative = [];
        $run = 0.0;
        for ($i=0; $i<$days; $i++) {
            $d = $start->copy()->addDays($i)->toDateString();
            $labels[] = $d;
            $v = (float) ($vals[$d] ?? 0.0);
            $daily[] = $v;
            $run += $v;
            $cumulative[] = round($run, 2);
        }

        return [
            'labels' => $labels,
            'daily' => $daily,
            'cumulative' => $cumulative,
            'target' => (float) $this->target_value,
            'unit' => $this->unit,
        ];
    }

    /**
     * Trend direction for last 7 days vs previous 7 days: up|down|flat
     */
    public function trendForUser(int $userId): string
    {
        $today = now()->startOfDay();
        $w1Start = (clone $today)->subDays(6);
        $w2Start = (clone $today)->subDays(13);

        $last7 = (float) $this->progresses()
            ->where('user_id', $userId)
            ->whereBetween('entry_date', [$w1Start->toDateString(), $today->toDateString()])
            ->sum('value');
        $prev7 = (float) $this->progresses()
            ->where('user_id', $userId)
            ->whereBetween('entry_date', [$w2Start->toDateString(), $w1Start->subDay()->toDateString()])
            ->sum('value');

        if ($prev7 <= 0 && $last7 <= 0) return 'flat';
        if ($last7 > $prev7 * 1.05) return 'up';
        if ($last7 < $prev7 * 0.95) return 'down';
        return 'flat';
    }

    public function lastUpdateForUser(int $userId): ?string
    {
        $d = $this->progresses()->where('user_id', $userId)->latest('entry_date')->value('entry_date');
        return $d ? (string) $d : null;
    }
}


