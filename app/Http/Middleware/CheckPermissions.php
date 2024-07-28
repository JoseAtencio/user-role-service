<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\ErrorResponse;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('sanctum')->user();
        $token = $user ? $user->currentAccessToken() : null;
        $abilities = [];
        if (!$user->role->permissions->isEmpty()) {
            $abilities = $user->role->permissions->pluck('code')->toArray();    
        }

        // Definir los permisos requeridos para cada tipo de solicitud
        $requiredPermissions = [
            'GET' => 'view',
            'POST' => 'create',
            'PUT' => 'edit',
            'DELETE' => 'delete',
            'PATCH' => 'edit',
        ];

        $method = $request->method();
        $resource = $request->route()->getName(); // Asumiendo que el nombre de la ruta sigue el formato 'resource.action'

        // Extraer el recurso de la ruta
        $resource = explode('.', $resource)[1];

        // Construir el permiso requerido
        $requiredPermission = $requiredPermissions[$method] . '_' . $resource;
       // dd($requiredPermission);
        if (!in_array($requiredPermission, $abilities)) {
            $error = Error::fromArray([
                'status' => '403',
                'title' => 'Forbidden',
                'detail' => 'You don\'t have the necessary permissions.'
            ]);
            $esxtruct_error = ErrorResponse::make($error)->withStatus(403);
            return response()->json($esxtruct_error, 403);
        }
        //dd($request);
        return $next($request);
    }
}