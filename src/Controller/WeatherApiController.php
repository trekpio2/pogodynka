<?php

namespace App\Controller;

use App\Entity\Forecast;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        WeatherUtil $weatherUtil,
        #[MapQueryParameter('city')] string $city,
        #[MapQueryParameter('country_code')] string $country_code = 'PL',
        #[MapQueryParameter(
            'format',
            filter: \FILTER_VALIDATE_REGEXP,
            options: ['regexp' => '/^(json|csv)$/i']
        )] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,

    ): Response
    {
        $forecasts = $weatherUtil->getWeatherForCountryAndCity($city, $country_code);
        if($format == 'csv') {
            if($twig) {
                return $this->render('weather_api/index.csv.twig', [
                   'city' => $city,
                   'country_code' => $country_code,
                   'forecasts' => $forecasts,
                ]);
            } else {
                $csv = "city, country_code, date, celsius, wind\n";
                $csv .= implode(
                    "\n",
                    array_map(fn(Forecast $f) => sprintf(
                        '%s,%s,%s,%s,%s,%s',
                        $city,
                        $country_code,
                        $f->getDate()->format('Y-m-d'),
                        $f->getTemperature(),
                        $f->getFahrenheit(),
                        $f->getWind(),
                    ), $forecasts)
                );
                return new Response($csv, Response::HTTP_OK, [
//                'Content-Type' => 'text/csv',
                ]);
            }
        } else {
            if ($twig) {
                return $this->render('weather_api/index.json.twig', [
                    'city' => $city,
                    'country_code' => $country_code,
                    'forecasts' => $forecasts,
                ]);

            } else {
                return $this->json([
                    'city' => $city,
                    'country_code' => $country_code,
                    'forecasts' => array_map(fn(Forecast $f) => [
                        'date' => $f->getDate()->format('Y-m-d'),
                        'celsius' => $f->getTemperature(),
                        'fahrenheit' => $f->getFahrenheit(),
                        'wind' => $f->getWind(),
                    ], $forecasts),
                ]);
            }
        }
    }
}
