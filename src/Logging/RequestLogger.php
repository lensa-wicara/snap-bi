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
     *
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * dispatch
     *
     * @param Response $response
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
     *
     * @return string
     */
    public function getLogLevel(): string
    {
        $logLevel = config('snap-bi.logging.log_level') ?? 'debug';

        return $logLevel;
    }

    /**
     * get request message
     *
     * @return string
     */
    public function getRequestMessage(Request $request): string
    {
        $message = [
            'method' => $request->getMethod(),
            'url' => $request->getUri(),
            'headers' => $request->getHeaders(),
            'body' => $request->getBody()->getContents(),
        ];

        // return combine all message to string
        return implode(':', $message);
    }

    /**
     * get request context
     *
     * @return void
     */
    public function getRequestContext(Request $request): array
    {
        $context = [
            'method' => $request->getMethod(),
            'url' => $request->getUri(),
            'headers' => $request->getHeaders(),
            'body' => $request->getBody()->getContents(),
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
        $context = [
            'statusCode' => $this->response->status(),
            'headers' => $this->response->headers(),
            'body' => $this->response->json(),
        ];

        return $context;
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