<?php
/**
 * Description of SensitiveDataFilter.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Tests\Filters;

use Dotsplatform\RequestsLogger\Filters\SensitiveDataFilter;
use Illuminate\Support\Str;
use Tests\TestCase;

class SensitiveDataFilterTest extends TestCase
{
    private function getSensitiveDataFilter(): SensitiveDataFilter
    {
        return app(SensitiveDataFilter::class);
    }

    /**
     * @dataProvider getTestFilterData
     */
    public function testFilter(array $expected, array $data): void
    {
        $data = $this->getSensitiveDataFilter()->filter($data);

        $this->assertEquals($expected, $data);
    }

    public static function getTestFilterData(): array
    {
        return [
            'empty' => [
                [],
                [],
            ],
            'test replaces url' => [
                [
                    'url' => 'https://google.com?key=secret&apiKey=secret',
                ],
                [
                    'url' => 'https://google.com?key=1232&apiKey=1123123',
                ],
            ],
            'replaced returned' => [
                [
                    'method' => 'GET',
                    'provider' => 'google',
                    'url' => 'https://google.com/geocode?key=secret&apiKey=secret',
                    'name' => SensitiveDataFilter::SECRET,
                    'password' => SensitiveDataFilter::SECRET,
                    'token' => SensitiveDataFilter::SECRET,
                    'apiToken' => SensitiveDataFilter::SECRET,
                    'api_key' => SensitiveDataFilter::SECRET,
                    'secret' => SensitiveDataFilter::SECRET,
                    'data' => [
                        'name' => SensitiveDataFilter::SECRET,
                        'method' => 'GET',
                        'password' => SensitiveDataFilter::SECRET,
                        'token' => SensitiveDataFilter::SECRET,
                        'secret' => SensitiveDataFilter::SECRET,
                    ],
                ],
                [
                    'method' => 'GET',
                    'provider' => 'google',
                    'url' => 'https://google.com/geocode?key=343&apiKey=aasd31234sdas',
                    'name' => 'Adaam',
                    'password' => '123456',
                    'token' => Str::random(),
                    'apiToken' => Str::random(),
                    'api_key' => Str::random(),
                    'secret' => Str::random(),
                    'data' => [
                        'name' => Str::random(),
                        'method' => 'GET',
                        'password' => Str::random(),
                        'token' => Str::random(),
                        'secret' => Str::random(),
                    ],
                ],
            ],
        ];
    }
}
