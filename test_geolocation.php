<?php

// Simple script to verify allowed countries in GeolocationRestriction middleware
require_once __DIR__ . '/vendor/autoload.php';

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

// Test some countries
$testCountries = [
    'US' => 'United States (Allowed)',
    'CA' => 'Canada (Allowed)',
    'GB' => 'United Kingdom (Allowed)',
    'AU' => 'Australia (Allowed)',
    'IN' => 'India (Allowed)',
    'BD' => 'Bangladesh (Should be Blocked)',
    'CN' => 'China (Should be Blocked)',
    'PK' => 'Pakistan (Should be Blocked)',
    'NG' => 'Nigeria (Should be Blocked)',
    'EG' => 'Egypt (Should be Blocked)',
];

echo "Geolocation Restriction Test Results:\n";
echo str_repeat("=", 50) . "\n";

foreach ($testCountries as $code => $description) {
    $status = in_array($code, $allowedCountries) ? "✅ ALLOWED" : "❌ BLOCKED";
    echo sprintf("%-2s - %-35s %s\n", $code, $description, $status);
}

echo "\nTotal Allowed Countries: " . count($allowedCountries) . "\n";

// Group by region for verification
$regions = [
    'North America' => array_slice($allowedCountries, 0, 23),
    'South America' => array_slice($allowedCountries, 23, 13),
    'Europe' => array_slice($allowedCountries, 36, 45),
    'Australia & Oceania' => array_slice($allowedCountries, 81, 16),
    'India' => ['IN']
];

echo "\nCountries by Region:\n";
foreach ($regions as $region => $countries) {
    echo "$region: " . count($countries) . " countries\n";
    echo "  " . implode(', ', $countries) . "\n\n";
}
