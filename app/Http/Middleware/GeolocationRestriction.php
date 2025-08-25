<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeolocationRestriction
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip geolocation check in local development
        if (app()->environment('local') || $request->ip() === '127.0.0.1' || $request->ip() === '::1') {
            return $next($request);
        }

        // Skip if already blocked or on blocked page
        if ($request->routeIs('access.blocked')) {
            return $next($request);
        }

        // Get user's IP address
        $userIP = $request->ip();

        // Use a free IP geolocation service
        try {
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$userIP}?fields=status,country,countryCode");

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === 'success') {
                    $country = $data['country'];
                    $countryCode = $data['countryCode'];

                    // List of allowed countries
                    $allowedCountries = [
                        // North America
                        'US', 'CA', 'MX', 'GT', 'BZ', 'SV', 'HN', 'NI', 'CR', 'PA',
                        'CU', 'JM', 'HT', 'DO', 'BS', 'BB', 'TT', 'GD', 'DM', 'AG',
                        'KN', 'LC', 'VC',

                        // South America
                        'BR', 'AR', 'CO', 'PE', 'VE', 'CL', 'EC', 'BO', 'PY', 'UY',
                        'GY', 'SR', 'GF',

                        // Europe
                        'GB', 'FR', 'DE', 'IT', 'ES', 'PT', 'NL', 'BE', 'CH', 'AT',
                        'SE', 'NO', 'DK', 'FI', 'IS', 'IE', 'LU', 'MT', 'CY', 'GR',
                        'PL', 'CZ', 'SK', 'HU', 'SI', 'HR', 'BA', 'RS', 'ME', 'MK',
                        'AL', 'BG', 'RO', 'MD', 'UA', 'BY', 'LT', 'LV', 'EE', 'RU',
                        'AD', 'MC', 'SM', 'VA', 'LI',

                        // Australia and Oceania
                        'AU', 'NZ', 'FJ', 'PG', 'SB', 'NC', 'PF', 'WS', 'VU', 'TO',
                        'TV', 'PW', 'NR', 'MH', 'KI', 'FM',

                        // India
                        'IN'
                    ];

                    if (!in_array($countryCode, $allowedCountries)) {
                        // Store blocked info in session for display
                        session([
                            'blocked_country' => $country,
                            'blocked_ip' => $userIP
                        ]);

                        return redirect()->route('access.blocked');
                    }
                }
            }
        } catch (\Exception $e) {
            // If geolocation fails, allow access (fail-safe)
            \Log::warning('Geolocation check failed: ' . $e->getMessage());
        }

        return $next($request);
    }
}
