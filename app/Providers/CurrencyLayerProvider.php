<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CurrencyLayerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $maxRetries = env('MAX_RETRIES', 3);
        Http::macro('currency_layer', function () use ($maxRetries) {
            return Http::withMiddleware(
                Middleware::retry(function ($retries,
                        Request $request,
                        Response $response = null,
                        RequestException $exception = null) use ($maxRetries) {
                            /**
                             * x-ratelimit-limit-month: Request limit per month
                             * x-ratelimit-remaining-month: Request limit remaining this month
                             * x-ratelimit-limit-day: Request limit per day
                             * x-ratelimit-remaining-day: Request limit remaining today
                             */
                            // \Log::info('error code '.$response->getStatusCode());
                            // \Log::info('Retry RateLimit-Reset '.json_encode($response->getHeader('x-ratelimit-limit-day')));
                            // \Log::info('Retry RateLimit-Remaining: '.json_encode($response->getHeader('RateLimit-Remaining')));

                            return
                            ($retries < $maxRetries
                            && null !== $response
                            && 429 === $response->getStatusCode()) //Exceded the rate limit
                            || (
                                $retries < $maxRetries
                                && null !== $response
                                && 500 === $response->getStatusCode() //Internal server error
                            );

                }, function(int $retries, Response $response) : int{
                    // \Log::info('delay ');
                    // \Log::info('RateLimit-Reset '.$response->getHeader('x-ratelimit-limit-day')[0]);
                    return isset($response->getHeader('x-ratelimit-limit-day')[0]) ? intval($response->getHeader('x-ratelimit-limit-day')) * 1000 : 10000; //delay in miliseconds
                })
            )->withHeaders([
                'Accept' => 'text/plain',
                "apikey" => env('CURRENCY_LAYER_API_KEY')
            ])->baseUrl(env('CURRENCY_LAYER_URL','https://api.apilayer.com').'/currency_data');
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
