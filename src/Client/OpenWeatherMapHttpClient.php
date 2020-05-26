<?php

namespace KotosWeather\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use KotosWeather\Entity\OpenWeatherMap\SuccessfulWeatherResponse;
use KotosWeather\Exception\OpenWeatherMapApiException;
use Symfony\Component\Serializer\Serializer;

class OpenWeatherMapHttpClient
{
    /** @var ClientInterface */
    private $guzzleClient;

    /** @var Serializer */
    private $serializer;

    /**
     * OpenWeatherMapHttpClient constructor.
     * @param ClientInterface $guzzleClient
     * @param Serializer $serializer
     */
    public function __construct(ClientInterface $guzzleClient, Serializer $serializer)
    {
        $this->guzzleClient = $guzzleClient;
        $this->serializer = $serializer;
    }

    /**
     * @param string $locale
     * @param string $apiHost
     * @param string $apiKey
     * @return SuccessfulWeatherResponse
     * @throws GuzzleException
     * @throws OpenWeatherMapApiException
     */
    public function getWeatherByCityNameUsingApiKey(string $locale, string $apiHost, string $apiKey): SuccessfulWeatherResponse
    {
        $uriParameters = [
            'zip' => $locale,
            'appid' => $apiKey
        ];
        $uriQueryString = \http_build_query($uriParameters);

        try {
            $request = new Request('GET', "$apiHost/data/2.5/weather?$uriQueryString");
            $response = $this->guzzleClient->send($request);
        } catch (RequestException $e) {
            throw new OpenWeatherMapApiException('Error communicating with Open Weather Map. Is your API key correct?');
        }

        $rawResponseBody = $response->getBody()->getContents();

        return $this->serializer->deserialize($rawResponseBody, SuccessfulWeatherResponse::class, 'json');
    }
}
