<?php

namespace Kebir\UniversalAnalytics;

interface CustomizableInterface
{
    /**
     * Add a custom dimension.
     *
     * @param int    $key   The custom dimension key.
     * @param string $value The value of the custom dimension.
     */
    public function setCustomDimension($key, $value);

    /**
     * Add a custom metrics.
     *
     * @param int    $key   The custom metric key.
     * @param string $value The value of the custom metric.
     */
    public function setCustomMetric($key, $value);
}
