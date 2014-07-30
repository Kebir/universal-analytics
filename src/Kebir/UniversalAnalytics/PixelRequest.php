<?php

namespace Kebir\UniversalAnalytics;

class PixelRequest implements Request
{
    /**
     * Call the url and returns the content.
     *
     * @param  string $url        The url.
     * @param  array  $parameters The parameters.
     *
     * @return mixed
     */
    public function get($url, $parameters = array())
    {
        if ($parameters) {
            $url .= '?'.$this->getQueryString($parameters);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 2
        ));

        $content = curl_exec($curl);

        curl_close($curl);

        return $content;
    }

    /**
     * Get the query string.
     *
     * @param  array $parameters The parameters list.
     *
     * @return string.
     */
    private function getQueryString($parameters)
    {
        if (defined('PHP_QUERY_RFC3986')) {
            $query = http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
        } else {
            $query = str_replace('+', '%20', http_build_query($parameters, '', '&'));
        }

        return $query;
    }
}
