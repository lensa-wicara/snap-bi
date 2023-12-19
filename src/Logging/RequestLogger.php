<?php

namespace LensaWicara\SnapBI\Logging;

use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class RequestLogger
{
    protected $response;

    /**
     * Constructor
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * dispatch
     *
     * @return static
     */
    public static function dispatch(Response $response)
    {
        return new static($response);
    }

    /**
     * get channel
     */
    public function getChannel(): string
    {
        $channel = config('snap-bi.logging.channel');

        return $channel;
    }

    /**
     * log level
     */
    public function getLogLevel(): string
    {
        $logLevel = config('snap-bi.logging.log_level') ?? 'debug';

        return $logLevel;
    }

    /**
     * get request message
     */
    public function getRequestMessage(Request $request): string
    {
        $message = [
            'status' => (string) $this->response->status(),
            'method' => (string) $request->getMethod(),
            'url' => (string) $request->getUri(),
        ];

        return collect($message)->join(' | ');
    }

    /**
     * get request context
     *
     * @return void
     */
    public function getRequestContext(Request $request): array
    {
        // hide sensitive data from headers and body
        $headers = $this->hideSensitiveData($request->getHeaders());
        $body = $this->hideSensitiveData($request->getBody()->getContents());

        $context = [
            'headers' => $headers,
            'body' => $body,
        ];

        return $context;
    }

    /**
     * get response context
     *
     * @return void
     */
    public function getResponseContext(): array
    {
        // hide sensitive data from headers and body
        $headers = $this->hideSensitiveData($this->response->headers());
        $body = $this->hideSensitiveData($this->response->json());

        $context = [
            'statusCode' => $this->response->status(),
            'headers' => $headers,
            'body' => $body,
        ];

        return $context;
    }

    /**
     * hide sensitive data from method get request context, get response context and get request message
     */
    public function hideSensitiveData($data)
    {
        // keys to hide
        $keys = [
            'Authorization',
            'Authorization-Customer',
            'X-CLIENT-SECRET',
            'X-CLIENT-KEY',
            'accessToken',
        ];

        // return
        if (is_array($data)) {
            return collect($data)->map(function ($value, $key) use ($keys) {
                if (in_array($key, $keys)) {
                    return '********';
                }

                return $value;
            })->toArray();
        }

        // if it string make it array to hide sensitive data
        if (is_string($data)) {
            $data = json_decode($data, true);

            return $this->hideSensitiveData($data);
        }

        return $data;
    }

    public function __destruct()
    {
        $request = $this->response->transferStats->getRequest();

        try {
            Log::channel($this->getChannel())->log($this->getLogLevel(),
                $this->getRequestMessage($request),
                [
                    'request' => $this->getRequestContext($request),
                    'response' => $this->getResponseContext(),
                ]);
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }
}
