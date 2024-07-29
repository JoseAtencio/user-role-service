<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;

class ImportExchangeRates extends Command
{
    protected $signature = 'exchange:import';
    protected $description = 'Import exchange rates from ECB';

    public function handle()
    {
        $url = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-hist.xml';
        $response = Http::get($url);

        if ($response->failed()) {
            $this->error('Failed to fetch exchange rates');
            return 1;
        }

        $xml = simplexml_load_string($response->body());
        $xml->registerXPathNamespace('gesmes', 'http://www.gesmes.org/xml/2002-08-01');
        $xml->registerXPathNamespace('Cube', 'http://www.ecb.int/vocabulary/2002-08-01/eurofxref');

        $data = $xml->xpath('//gesmes:Envelope//Cube:Cube//Cube:Cube');

        foreach ($data as $day) {
            $date = (string) $day['time'];

            foreach ($day->Cube as $rate) {
                $currency = (string) $rate['currency'];
                $rateValue = (float) $rate['rate'];

                ExchangeRate::updateOrCreate(
                    ['date' => $date, 'currency' => $currency],
                    ['rate' => $rateValue]
                );
            }
        }

        $this->info('Exchange rates imported successfully');
        return 0;
    }
}