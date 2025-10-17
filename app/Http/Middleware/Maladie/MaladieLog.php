<?php

namespace App\Http\Middleware\Maladie;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MaladieLog
{
    /**
     * Handle an incoming request.
     * Logs maladie-related activities for audit.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log only if response is successful (status 200-299)
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    /**
     * Log the activity.
     */
    private function logActivity(Request $request, Response $response): void
    {
        $action = $this->getAction($request);
        $maladieId = $request->route('maladie');
        $userId = Auth::id();

        $logData = [
            'user_id' => $userId,
            'action' => $action,
            'method' => $request->method(),
            'url' => $request->url(),
            'maladie_id' => $maladieId,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ];

        Log::channel('maladie_activity')->info("Maladie activity: {$action}", $logData);
    }

    /**
     * Determine action based on route name and method.
     */
    private function getAction(Request $request): string
    {
        $method = $request->method();
        $routeName = $request->route()->getName() ?? '';

        if (str_contains($routeName, 'toggle-status')) {
            return 'toggle_status';
        }

        switch ($method) {
            case 'GET':
                if (str_contains($routeName, 'index')) {
                    return 'list';
                } elseif (str_contains($routeName, 'create')) {
                    return 'create_form';
                } elseif (str_contains($routeName, 'edit')) {
                    return 'edit_form';
                } else {
                    return 'view';
                }
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
