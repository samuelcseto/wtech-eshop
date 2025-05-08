<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\ShippingProvider;
use Illuminate\Database\Seeder;

class CountryAndShippingProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create countries with Slovak names
        $countries = [
            [
                'code' => 'SK',
                'name' => 'Slovensko',
                'is_active' => true
            ],
            [
                'code' => 'CZ',
                'name' => 'Česko',
                'is_active' => true
            ],
            [
                'code' => 'HU',
                'name' => 'Maďarsko',
                'is_active' => true
            ],
            [
                'code' => 'AT',
                'name' => 'Rakúsko',
                'is_active' => true
            ],
            [
                'code' => 'PL',
                'name' => 'Poľsko',
                'is_active' => true
            ],
            [
                'code' => 'DE',
                'name' => 'Nemecko',
                'is_active' => false
            ]
        ];

        // Define multiple shipping providers
        $shippingProviders = [
            // Slovakia shipping providers
            [
                'country_code' => 'SK',
                'name' => 'Slovenská pošta',
                'description' => 'Oficiálna poštová služba pre Slovensko',
                'is_active' => true,
                'cost_calculation_method' => 'fixed',
                'price' => 2.49
            ],
            [
                'country_code' => 'SK',
                'name' => 'GLS Kuriér',
                'description' => 'Kuriérska služba GLS pre Slovensko',
                'is_active' => true,
                'cost_calculation_method' => 'weight',
                'price' => 3.99
            ],
            [
                'country_code' => 'SK',
                'name' => 'Packeta (Zásielkovňa)',
                'description' => 'Doručenie cez Zásielkovňu',
                'is_active' => true,
                'cost_calculation_method' => 'fixed',
                'price' => 2.29
            ],
            [
                'country_code' => 'SK',
                'name' => 'DPD Kuriér',
                'description' => 'Kuriérska služba DPD pre Slovensko',
                'is_active' => true,
                'cost_calculation_method' => 'weight',
                'price' => 4.29
            ],
            // Czech Republic shipping providers
            [
                'country_code' => 'CZ',
                'name' => 'Česká pošta',
                'description' => 'Oficiálna poštová služba pre Česko',
                'is_active' => true,
                'cost_calculation_method' => 'fixed',
                'price' => 3.49
            ],
            [
                'country_code' => 'CZ',
                'name' => 'PPL',
                'description' => 'Kuriérska služba PPL pre Česko',
                'is_active' => true,
                'cost_calculation_method' => 'weight',
                'price' => 4.49
            ],
            [
                'country_code' => 'CZ',
                'name' => 'Zásilkovna',
                'description' => 'Doručenie cez Zásilkovnu',
                'is_active' => true,
                'cost_calculation_method' => 'fixed',
                'price' => 2.99
            ],
            // Hungary shipping providers
            [
                'country_code' => 'HU',
                'name' => 'Magyar Posta',
                'description' => 'Oficiálna poštová služba pre Maďarsko',
                'is_active' => true,
                'cost_calculation_method' => 'fixed',
                'price' => 4.99
            ],
            [
                'country_code' => 'HU',
                'name' => 'GLS Magyarország',
                'description' => 'GLS doručenie v Maďarsku',
                'is_active' => true,
                'cost_calculation_method' => 'weight',
                'price' => 5.49
            ],
            // Austria shipping providers
            [
                'country_code' => 'AT',
                'name' => 'Österreichische Post',
                'description' => 'Oficiálna poštová služba pre Rakúsko',
                'is_active' => true,
                'cost_calculation_method' => 'fixed',
                'price' => 5.99
            ],
            [
                'country_code' => 'AT',
                'name' => 'DPD Austria',
                'description' => 'Kuriérska služba DPD pre Rakúsko',
                'is_active' => true,
                'cost_calculation_method' => 'weight',
                'price' => 6.49
            ],
            // Poland shipping providers
            [
                'country_code' => 'PL',
                'name' => 'Poczta Polska',
                'description' => 'Oficiálna poštová služba pre Poľsko',
                'is_active' => true,
                'cost_calculation_method' => 'fixed',
                'price' => 4.49
            ],
            [
                'country_code' => 'PL',
                'name' => 'InPost',
                'description' => 'InPost doručenie pre Poľsko',
                'is_active' => true,
                'cost_calculation_method' => 'weight',
                'price' => 3.99
            ],
            // Germany shipping providers (inactive country)
            [
                'country_code' => 'DE',
                'name' => 'Deutsche Post',
                'description' => 'Oficiálna poštová služba pre Nemecko',
                'is_active' => false,
                'cost_calculation_method' => 'fixed',
                'price' => 5.99
            ],
            [
                'country_code' => 'DE',
                'name' => 'DHL',
                'description' => 'DHL doručenie pre Nemecko',
                'is_active' => false,
                'cost_calculation_method' => 'weight',
                'price' => 6.99
            ]
        ];

        // Create countries
        $countryMap = [];
        foreach ($countries as $countryData) {
            $country = Country::create($countryData);
            $countryMap[$countryData['code']] = $country->id;
        }

        // Create shipping providers for each country
        foreach ($shippingProviders as $providerData) {
            $countryCode = $providerData['country_code'];
            unset($providerData['country_code']);
            
            // Assign the country_id based on the country code
            $providerData['country_id'] = $countryMap[$countryCode];
            
            // Create the shipping provider
            ShippingProvider::create($providerData);
        }
    }
}
