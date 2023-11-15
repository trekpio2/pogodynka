<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:cityAndCountry',
    description: 'Displays forecasts for location by city name and country code ',
)]
class WeatherCityAndCountryCommand extends Command
{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('city', InputArgument::REQUIRED, 'City name')
            ->addArgument('country_code', InputArgument::OPTIONAL, 'Country code', 'PL')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $city = $input->getArgument('city');
        $country_code = $input->getArgument('country_code');

        $forecasts = $this->weatherUtil->getWeatherForCountryAndCity($city, $country_code);

        $io->writeln(sprintf('Location: %s', $city));
        foreach ($forecasts as $forecast) {
            $io->writeln(sprintf("\t%s: %s, %s km/h",
                $forecast->getDate()->format('Y-m-d'),
                $forecast->getTemperature(),
                $forecast->getWind()
            ));
        }

        return Command::SUCCESS;
    }
}
