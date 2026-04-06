<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    public function getCurrentWeather(): JsonResponse
    {
        $city = config('services.openweather.city');
        $apiKey = config('services.openweather.key');

        // For dont extend tokens request API
        $weather = Cache::remember('current_weather', 1800, function () use ($city, $apiKey) {
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather?appid={$apiKey}&q={$city}&units=metric&lang=pt");

            return $response->json();
        });

        return response()->json($weather);
    }
}
