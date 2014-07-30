<?php

namespace Kebir\UniversalAnalytics;

interface Tracker
{
    /**
     * Set the campaign source.
     *
     * @param string $source The source.
     */
    public function setCampaignSource($source);

    /**
     * Set the campaign name.
     *
     * @param string $campaign The campaign name.
     */
    public function setCampaignName($campaign);

    /**
     * Set the campaign medium.
     *
     * @param string $medium The medium.
     */
    public function setCampaignMedium($medium);

    public function trackPageView($page);

    /**
     * Tracks the event into analytics.
     *
     * @param  string  $category The event category.
     * @param  string  $action   The event action.
     * @param  string  $label    The event label.
     * @param  integer $value    The event value.
     */
    public function trackEvent($category, $action, $label = '', $value = 0);

    /**
     * Tracks the transaction and items into analytics.
     *
     * @param  array  $items          The transaction items.
     * @param  mixed  $transaction_id The transaction id.
     * @param  double $total          The transaction total.
     * @param  string $affiliation    The transaction affiliation (Usually the company name).
     */
    public function trackTransaction($items, $transaction_id, $total, $affiliation = '');
}
