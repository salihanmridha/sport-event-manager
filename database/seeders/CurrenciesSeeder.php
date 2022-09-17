<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            ['code'  => 'USD', 'name' => 'US Dollar', 'symbol' => 'US$'],
            ['code'  => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            ['code'  => 'JPY', 'name' => 'Yen', 'symbol' => '¥'],
            ['code'  => 'GBP', 'name' => 'Pound Sterling', 'symbol' => '£'],
            ['code'  => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$'],
            ['code'  => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$'],
            ['code'  => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'CHF'],
            ['code'  => 'CNY', 'name' => 'Yuan', 'symbol' => '¥'],
            ['code'  => 'HKD', 'name' => 'Hong Kong Dollar', 'symbol' => 'HK$'],
            ['code'  => 'NZD', 'name' => 'New Zealand Dollar', 'symbol' => 'NZ$'],
            ['code'  => 'SEK', 'name' => 'Swedish Krona', 'symbol' => 'kr'],
            ['code'  => 'KRW', 'name' => 'South Korean Won', 'symbol' => '₩'],
            ['code'  => 'SGD', 'name' => 'Singapore Dollar', 'symbol' => 'S$'],
            ['code'  => 'NOK', 'name' => 'Norwegian Krone', 'symbol' => 'kr'],
            ['code'  => 'MXN', 'name' => 'Mexican Peso', 'symbol' => '$'],
            ['code'  => 'INR', 'name' => 'Indian Rupee', 'symbol' => '₹'],
            ['code'  => 'RUB', 'name' => 'Russian Ruble', 'symbol' => '₽'],
            ['code'  => 'ZAR', 'name' => 'Rand', 'symbol' => 'R'],
            ['code'  => 'TRY', 'name' => 'Turkish Lira', 'symbol' => '₺'],
            ['code'  => 'BRL', 'name' => 'Brazilian Real', 'symbol' => 'R$'],
            ['code'  => 'TWD', 'name' => 'Taiwan Dollar', 'symbol' => 'NT$'],
            ['code'  => 'DKK', 'name' => 'Danish Krone', 'symbol' => 'kr'],
            ['code'  => 'PLN', 'name' => 'PZloty', 'symbol' => 'zł'],
            ['code'  => 'THB', 'name' => 'Baht', 'symbol' => '฿'],
            ['code'  => 'IDR', 'name' => 'Rupiah', 'symbol' => 'Rp'],
            ['code'  => 'HUF', 'name' => 'Forint', 'symbol' => 'Ft'],
            ['code'  => 'CZK', 'name' => 'Czech Koruna', 'symbol' => 'Kč'],
            ['code'  => 'ILS', 'name' => 'New Israeli Shekel', 'symbol' => '₪'],
            ['code'  => 'CLP', 'name' => 'Chilean Peso', 'symbol' => 'CLP$'],
            ['code'  => 'PHP', 'name' => 'Philippine Peso', 'symbol' => '₱'],
            ['code'  => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ'],
            ['code'  => 'COP', 'name' => 'Colombian Peso', 'symbol' => 'COL$'],
            ['code'  => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => '﷼'],
            ['code'  => 'MYR', 'name' => 'Malaysian Ringgit', 'symbol' => 'RM'],
            ['code'  => 'RON', 'name' => 'Leu', 'symbol' => 'L']
        ];

        $now = now();

        $currencies = array_map(function ($item) use ($now) {
            return array_merge($item, ['created_at' => $now, 'updated_at' => $now]);
        }, $currencies);

        Currency::insert($currencies);
    }
}
