<?php

namespace Kebir\UniversalAnalytics;

class UniversalAnalyticsTracker implements Tracker, CustomizableInterface
{

    const PROTOCOL_VERSION = 'v';
    const ENDPOINTHOST = 'http://www.google-analytics.com';
    const ENDPOINTPATH = '/collect';
    const TIMEOUT = 1;

    const HIT_TYPE = 't';
    const CLIENT_ID = 'cid';
    const TRACKER_ID = 'tid';

    const CAMPAIGN_SOURCE = 'cs';
    const CAMPAIGN_NAME = 'cn';
    const CAMPAIGN_MEDIUM = 'cm';

    const PAGE = 'dp';

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
    const EVENT_HIT_TYPE = 'event';
    const TRANSACTION_HIT_TYPE = 'transaction';
    const ITEM_HIT_TYPE = 'item';

    const CUSTOM_DIMENSION = 'cd';
    const CUSTOM_METRIC = 'cm';

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
     * The custom dimensions.
     *
     * @var array
     */
    protected $custom_dimensions = array();

    /**
     * The custom metrics.
     *
     * @var array
     */
    protected $custom_metrics = array();

    /**
     * The campaign informations.
     *
     * @var array
     */
    protected $campaign_infos = array();

    /**
     * @param string $account   The analytics account.
     * @param string $client_id The client id.
     * @param string $request   The pixel "requester".
     */
    public function __construct($account, $client_id, Request $request)
    {
        $this->account = $account;
        $this->client_id = $client_id;
        $this->request = $request;
    }

    /**
     * Add a custom dimension.
     *
     * @param int    $key   The custom dimension key.
     * @param string $value The value of the custom dimension.
     */
    public function setCustomDimension($key, $value)
    {
        $this->custom_dimensions[static::CUSTOM_DIMENSION.$key] = $value;
    }

    /**
     * Add a custom metric.
     *
     * @param int    $key   The custom metric key.
     * @param string $value The value of the custom metric.
     */
    public function setCustomMetric($key, $value)
    {
        $this->custom_metrics[static::CUSTOM_METRIC.$key] = $value;
    }

    /**
     * Set the campaign source.
     *
     * @param string $campaign_source The source.
     */
    public function setCampaignSource($campaign_source)
    {
        $this->campaign_infos[static::CAMPAIGN_SOURCE] = $campaign_source;
    }

    /**
     * Set the campaign name.
     *
     * @param string $campaign_name The campaign name.
     */
    public function setCampaignName($campaign_name)
    {
        $this->campaign_infos[static::CAMPAIGN_NAME] = $campaign_name;
    }

    /**
     * Set the campaign medium.
     *
     * @param string $campaign_medium The medium.
     */
    public function setCampaignMedium($campaign_medium)
    {
        $this->campaign_infos[static::CAMPAIGN_MEDIUM] = $campaign_medium;
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
        $extra_parameters = array(
            static::CLIENT_ID => $this->client_id,
            static::TRACKER_ID => $this->account,
            static::PROTOCOL_VERSION => 1,
        );

        return array_merge(
            $options,
            $this->custom_dimensions,
            $this->custom_metrics,
            $this->campaign_infos,
            $extra_parameters
        );
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
}
