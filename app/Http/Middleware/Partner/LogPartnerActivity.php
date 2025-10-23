<?php

namespace App\Http\Middleware\Partner;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogPartnerActivity
{
    /**
     * Handle an incoming request.
     * Log les activités des partenaires pour audit
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
        $partnerId = $request->route('partner');
        $userId = Auth::id();
        
        $logData = [
            'user_id' => $userId,
            'action' => $action,
            'method' => $request->method(),
            'url' => $request->url(),
            'partner_id' => $partnerId,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ];
        
        Log::channel('partner_activity')->info("Partner activity: {$action}", $logData);
    }
    
    /**
     * Déterminer l'action basée sur la route et méthode
     */
    private function getAction(Request $request): string
    {
        $method = $request->method();
        $routeName = $request->route()->getName();
        
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
                if (str_contains($routeName, 'toggle-favorite')) {
                    return 'toggle_favorite';
                }
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