<?php

namespace App\Http\Middleware\Asymptome;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AsymptomeLog
{
    /**
     * Handle an incoming request.
     * Log les activités des asymptomes pour audit
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Logger seulement si l'action a réussi (codes 200-299)
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    /**
     * Log l'activité
     */
    private function logActivity(Request $request, Response $response): void
    {
        $action = $this->getAction($request);
        $asymptomeId = $request->route('asymptome'); // route param name
        $userId = Auth::id();

        $logData = [
            'user_id' => $userId,
            'action' => $action,
            'method' => $request->method(),
            'url' => $request->url(),
            'asymptome_id' => $asymptomeId,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ];

        Log::channel('asymptome_activity')->info("Asymptome activity: {$action}", $logData);
    }

    /**
     * Déterminer l'action basée sur la route et méthode
     */
    private function getAction(Request $request): string
    {
        $method = $request->method();
        $routeName = $request->route()->getName();

        switch ($method) {
            case 'GET':
                if (str_contains($routeName, 'index')) return 'list';
                if (str_contains($routeName, 'create')) return 'create_form';
                if (str_contains($routeName, 'edit')) return 'edit_form';
                return 'view';
            case 'POST':
                return 'create';
            case 'PUT':
            case 'PATCH':
                return 'update';
            case 'DELETE':
                return 'delete';
            default:
                return 'unknown';
        }
    }
}
