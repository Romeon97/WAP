<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subscription;

class CheckSubscriptionToken
{
    public function handle(Request $request, Closure $next)
    {
        //Haal de Authorization-header op en probeer het Bearer token eruit te halen
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return response()->json(['message' => 'Unauthorized: Token missing'], 401);
        }
        $providedToken = $matches[1];

        //Haal de subscription op via het routeparameter identifier
        $identifier = $request->route('identifier');
        if (!$identifier) {
            return response()->json(['message' => 'Subscription identifier missing'], 400);
        }

        $subscription = Subscription::where('identifier', $identifier)->first();
        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        //Vergelijk de token
        if ($subscription->token !== $providedToken) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        //Voeg de gevonden subscription toe aan de request
        $request->attributes->add(['subscription' => $subscription]);

        return $next($request);
    }
}
