<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\ForecastRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{country_code}', name: 'app_weather')]
    #[IsGranted('ROLE_WEATHER_CITY')]
    public function city(
        #[MapEntity(mapping: ['city' => 'city', 'country_code' => 'country_code'])]
        Location $location,
        ForecastRepository $repository,
        string $country_code = 'PL'): Response
    {
        $measurements = $repository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);

    }
}
