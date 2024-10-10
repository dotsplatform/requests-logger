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

    public function testFilterExpectsClientSensitiveKeysUsed(): void
    {
        config()->set('requests-logger.sensitive_keys', 'clientKey,clientKey2');
        $data = [
            'method' => 'GET',
            'provider' => 'google',
            'clientKey' => 'value',
            'data' => [
                'clientKey2' => 'value2',
            ],
        ];
        $result = $this->getSensitiveDataFilter()->filter($data);
        $expected = [
            'method' => 'GET',
            'provider' => 'google',
            'clientKey' => SensitiveDataFilter::SECRET,
            'data' => [
                'clientKey2' => SensitiveDataFilter::SECRET,
            ],
        ];

        $this->assertEquals($expected, $result);
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
            'test with null values' => [
                [
                    'method' => 'GET',
                    'provider' => null,
                    'account' => null,
                    'url' => 'https://google.com/geocode?key=secret&apiKey=secret',
                    'name' => SensitiveDataFilter::SECRET,
                    'password' => SensitiveDataFilter::SECRET,
                    'token' => 'token',
                    'apiToken' => 'apiToken',
                    'api_key' => SensitiveDataFilter::SECRET,
                    'secret' => SensitiveDataFilter::SECRET,
                    'data' => [
                        'name' => SensitiveDataFilter::SECRET,
                        'method' => 'GET',
                        'password' => SensitiveDataFilter::SECRET,
                        'token' => 'token',
                        'secret' => SensitiveDataFilter::SECRET,
                        'type' => null,
                    ],
                ],
                [
                    'method' => 'GET',
                    'provider' => null,
                    'account' => null,
                    'url' => 'https://google.com/geocode?key=343&apiKey=aasd31234sdas',
                    'name' => 'Adaam',
                    'password' => '123456',
                    'token' => 'token',
                    'apiToken' => 'apiToken',
                    'api_key' => Str::random(),
                    'secret' => Str::random(),
                    'data' => [
                        'name' => null,
                        'method' => 'GET',
                        'password' => Str::random(),
                        'token' => 'token',
                        'secret' => Str::random(),
                        'type' => null,
                    ],
                ],
            ],
            'replaced returned' => [
                [
                    'method' => 'GET',
                    'provider' => 'google',
                    'url' => 'https://google.com/geocode?key=secret&apiKey=secret',
                    'name' => SensitiveDataFilter::SECRET,
                    'password' => SensitiveDataFilter::SECRET,
                    'token' => 'token',
                    'apiToken' => 'apiToken',
                    'api_key' => SensitiveDataFilter::SECRET,
                    'secret' => SensitiveDataFilter::SECRET,
                    'data' => [
                        'name' => SensitiveDataFilter::SECRET,
                        'method' => 'GET',
                        'password' => SensitiveDataFilter::SECRET,
                        'token' => 'token',
                        'secret' => SensitiveDataFilter::SECRET,
                    ],
                ],
                [
                    'method' => 'GET',
                    'provider' => 'google',
                    'url' => 'https://google.com/geocode?key=343&apiKey=aasd31234sdas',
                    'name' => 'Adaam',
                    'password' => '123456',
                    'token' => 'token',
                    'apiToken' => 'apiToken',
                    'api_key' => Str::random(),
                    'secret' => Str::random(),
                    'data' => [
                        'name' => Str::random(),
                        'method' => 'GET',
                        'password' => Str::random(),
                        'token' => 'token',
                        'secret' => Str::random(),
                    ],
                ],
            ],
            'replaced json' => [
                [
                    'method' => 'GET',
                    'provider' => 'google',
                    'url' => 'https://google.com/geocode?key=secret&apiKey=secret',
                    'name' => SensitiveDataFilter::SECRET,
                    'password' => SensitiveDataFilter::SECRET,
                    'token' => 'token',
                    'apiToken' => 'apiToken',
                    'api_key' => SensitiveDataFilter::SECRET,
                    'secret' => SensitiveDataFilter::SECRET,
                    'body' => json_encode([
                        'apiKey' => 'secret',
                        'key' => 'secret',
                    ]),
                    'data' => [
                        'name' => SensitiveDataFilter::SECRET,
                        'method' => 'GET',
                        'password' => SensitiveDataFilter::SECRET,
                        'token' => 'token',
                        'secret' => SensitiveDataFilter::SECRET,
                    ],
                ],
                [
                    'method' => 'GET',
                    'provider' => 'google',
                    'url' => 'https://google.com/geocode?key=343&apiKey=aasd31234sdas',
                    'name' => 'Adaam',
                    'password' => '123456',
                    'token' => 'token',
                    'apiToken' => 'apiToken',
                    'api_key' => Str::random(),
                    'secret' => Str::random(),
                    'body' => json_encode([
                        'apiKey' => 'abc',
                        'key' => '1234',
                    ]),
                    'data' => [
                        'name' => Str::random(),
                        'method' => 'GET',
                        'password' => Str::random(),
                        'token' => 'token',
                        'secret' => Str::random(),
                    ],
                ],
            ],
        ];
    }
}
