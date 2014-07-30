<?php

namespace Kebir\UniversalAnalytics;

interface Request
{
    /**
     * Call the url and returns the content.
     *
     * @param  string $url        The url.
     * @param  array  $parameters The parameters.
     *
     * @return mixed
     */
    public function get($url, $parameters = array());
}
