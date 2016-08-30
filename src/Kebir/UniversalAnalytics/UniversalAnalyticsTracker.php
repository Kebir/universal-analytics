<?php

namespace Kebir\UniversalAnalytics;

class UniversalAnalyticsTracker
{

    const PROTOCOL_VERSION = 'v';
    const ENDPOINTHOST = 'http://www.google-analytics.com';
    const ENDPOINTPATH = '/collect';
    const TIMEOUT = 1;

    const HIT_TYPE = 't';
    const CLIENT_ID = 'cid';
    const TRACKER_ID = 'tid';

    const PAGE = 'dp';
    const SCREEN_NAME = 'cd';

    const EVENT_CATEGORY = 'ec';
    const EVENT_ACTION = 'ea';
    const EVENT_LABEL = 'el';
    const EVENT_VALUE = 'ev';

    const TRANSACTION_ID = 'ti';
    const TRANSACTION_REVENUE = 'tr';
    const TRANSACTION_AFFILIATION = 'ta';

    const ITEM_NAME = 'in';
    const ITEM_PRICE = 'ip';
    const ITEM_QUANTITY = 'iq';
    const ITEM_CODE = 'ic';
    const ITEM_CATEGORY = 'iv';

    const PAGEVIEW_HIT_TYPE = 'pageview';
    const SCREENVIEW_HIT_TYPE = 'screenview';
    const EVENT_HIT_TYPE = 'event';
    const TRANSACTION_HIT_TYPE = 'transaction';
    const ITEM_HIT_TYPE = 'item';

    const CUSTOM_DIMENSION = 'cd';
    const CUSTOM_METRIC = 'cm';

    /*
     |----------------------------------------------
     | Availables functions
     |----------------------------------------------
     | Contains the list of availables fields the
     | user can set using "magic" functions.
     |
     | Examples:
     |     ->setCampaignSource($source);
     |     ->setExperimentId($experiment);
     |
     */
    protected $available_parameters = array(
        "CampaignSource" => "cs",
        "CampaignName" => "cn",
        "CampaignMedium" => "cm",
        "CampaignKeyword" => "ck",
        "CampaignContent" => "cc",
        "CampaignId" => "ci",
        "GoogleAdwordsId" => "gclid",
        "GoogleDisplayAdsId" => "dclid",
        "ScreenResolution" => "sr",
        "ViewportSize" => "vp",
        "DocumentEncoding" => "de",
        "ScreenColors" => "sd",
        "UserLanguage" => "ul",
        "NonInteractionHit" => "ni",
        "DocumentLocationUrl" => "dl",
        "DocumentHostname" => "dh",
        "DocumentPath" => "dp",
        "DocumentTitle" => "dt",
        "ApplicationName" => "an",
        "ApplicationId" => "aid",
        "ApplicationVersion" => "av",
        "ApplicationInstallerId" => "aiid",
        "SessionControl" => "sc",
        "Ip" => "uip",
        "UserAgent" => "ua",
        "CountryCode" => "geoid",
        "UserLanguage" => "ul",
        "NonInteraction" => "ni",
        "ExperimentId" => "xid",
        "ExperimentVariation" => "xvar",
        "QueueTime" => "qt",
        "DocumentReferrer" => "dr"
    );

    /**
     * The analytics account id.
     *
     * @var boolean
     */
    protected $account;

    /**
     * The client id.
     *
     * @var string
     */
    protected $client_id;

    /**
     * The pixel request.
     *
     * @var Kebir\UniversalAnalytics\Request
     */
    protected $request;

    /**
     * The parameters to send to analytics.
     *
     * @var array
     */
    protected $parameters = array();

    /**
     * @param string $request   The pixel "requester".
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Set the account.
     *
     * @param string $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * Set the client id.
     *
     * @param string $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * Add a custom dimension.
     *
     * @param int    $key   The custom dimension key.
     * @param string $value The value of the custom dimension.
     */
    public function setCustomDimension($key, $value)
    {
        $this->setParameter(static::CUSTOM_DIMENSION.$key, $value);
    }

    /**
     * Add a custom metric.
     *
     * @param int    $key   The custom metric key.
     * @param string $value The value of the custom metric.
     */
    public function setCustomMetric($key, $value)
    {
        $this->setParameter(static::CUSTOM_METRIC.$key, $value);
    }

    /**
     * Removes all parameters.
     */
    public function clearData()
    {
        $this->parameters = array();
    }

    /**
     * Tracks the page into analytics.
     *
     * @param string $page The page.
     */
    public function trackPageView($page)
    {
        $this->track(array(
            static::PAGE => $page,
            static::HIT_TYPE => static::PAGEVIEW_HIT_TYPE
        ));
    }
    
    /**
     * Tracks the screen view into analytics.
     *
     * @param string $screen_name The screen name.
     */
    public function trackScreenView($screen_name)
    {
        $this->track(array(
            static::SCREEN_NAME => $screen_name,
            static::HIT_TYPE => static::SCREENVIEW_HIT_TYPE
        ));
    }

    /**
     * Tracks the event into analytics.
     *
     * @param  string  $category The event category.
     * @param  string  $action   The event action.
     * @param  string  $label    The event label.
     * @param  integer $value    The event value.
     */
    public function trackEvent($category, $action, $label = '', $value = 0)
    {
        $this->track(array(
            static::EVENT_CATEGORY => $category,
            static::EVENT_ACTION => $action,
            static::EVENT_LABEL => $label,
            static::EVENT_VALUE => $value,
            static::HIT_TYPE => static::EVENT_HIT_TYPE
        ));
    }

    /**
     * Tracks the transaction and items into analytics.
     *
     * @param  array  $items          The transaction items.
     * @param  mixed  $transaction_id The transaction id.
     * @param  double $total          The transaction total.
     * @param  string $affiliation    The transaction affiliation (Usually the company name).
     */
    public function trackTransaction($items, $transaction_id, $total, $affiliation = '')
    {
        //First we send the transaction infos.
        $this->track(array(
            static::TRANSACTION_ID => $transaction_id,
            static::TRANSACTION_AFFILIATION => $affiliation,
            static::TRANSACTION_REVENUE => $total,
            static::HIT_TYPE => static::TRANSACTION_HIT_TYPE
        ));

        //And we send each items.
        foreach ($items as $item) {
            $this->track(array_merge($item, array(
                static::TRANSACTION_ID => $transaction_id,
                static::HIT_TYPE => static::ITEM_HIT_TYPE
            )));
        }
    }

    /**
     * Sends the data to Google Analytics.
     *
     * @param array $gif_options The request's options(optionnal).
     */
    private function track($options)
    {
        if (!$this->client_id && !$this->account) {
            return;
        }

        $params = $this->buildRequestParameters($options);

        $url = static::ENDPOINTHOST.static::ENDPOINTPATH;

        return $this->request->get($url, $params);
    }

    /**
     * Build the request parameters.
     *
     * @param  array $options The request options.
     *
     * @return array
     */
    private function buildRequestParameters($options)
    {
        $options[static::CLIENT_ID] = $this->client_id;
        $options[static::TRACKER_ID] = $this->account;
        $options[static::PROTOCOL_VERSION] = 1;

        return array_merge($options, $this->parameters);
    }

    /**
     * Adds a parameter to the list.
     *
     * @param string $name  The name of the parameter.
     * @param mixed  $value The value to send.
     */
    private function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Creates a valid item.
     *
     * @param  string  $name     The item name.
     * @param  double  $price    The item price.
     * @param  integer $quantity The item quantity.
     * @param  string  $code     The item code.
     * @param  string  $category The item category.
     *
     * @return array
     */
    public static function createItem($name, $price, $quantity = 1, $code = '', $category = '')
    {
        return array(
            static::ITEM_NAME => $name,
            static::ITEM_PRICE => $price,
            static::ITEM_QUANTITY => $quantity,
            static::ITEM_CODE => $code ? $code : $name,
            static::ITEM_CATEGORY => $category
        );
    }

    public function __call($name, $arguments)
    {
        if (!isset($arguments[0])) {
            throw new \InvalidArgumentException("Missing argument #1 for method $name");
        }

        $parameter_name = substr($name, 3);
        if (strpos($name, "set") !== 0 || !isset($this->available_parameters[$parameter_name])) {
            throw new MethodDoesNotExistException("Call to undefined method $name");
        }


        $parameter_key = $this->available_parameters[$parameter_name];

        return $this->setParameter($parameter_key, $arguments[0]);
    }
}
