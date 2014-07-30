<?php

use Kebir\UniversalAnalytics\UniversalAnalyticsTracker;

class UniversalAnalyticsTrackerTest extends PHPUnit_Framework_TestCase
{
    /**
     * The request.
     *
     * @var Kebir\UniversalAnalytics\Request
     */
    protected $request;

    protected $account = 'ACCOUNT';
    protected $client_id = 'CLIENT_ID';

    public function setUp()
    {
        $this->request = Mockery::mock('Kebir\UniversalAnalytics\Request');

        $this->tracker = new UniversalAnalyticsTracker($this->account, $this->client_id, $this->request);
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function it_tracks_a_page()
    {
        $page = '/momo/test';

        $this->request->shouldReceive('get')->with('http://www.google-analytics.com/collect', array(
            'dp' => $page,
            't' => 'pageview',
            'cid' => $this->client_id,
            'tid' => $this->account,
            'v' => 1
        ));

        $this->tracker->trackPageView($page);
    }

    /**
     * @test
     * @dataProvider events
     */
    public function it_tracks_an_event($category, $action, $label, $value)
    {
        $this->request->shouldReceive('get')->with('http://www.google-analytics.com/collect', array(
            'ec' => $category,
            'ea' => $action,
            'el' => $label,
            'ev' => $value,
            't' => 'event',
            'cid' => $this->client_id,
            'tid' => $this->account,
            'v' => 1
        ));

        $this->tracker->trackEvent($category, $action, $label, $value);
    }

    /**
     * @test
     * @dataProvider campaigns
     */
    public function it_tracks_a_page_with_campaign_infos($source, $campaign, $medium)
    {
        $page = '/momo/test';

        $this->request->shouldReceive('get')->with('http://www.google-analytics.com/collect', array(
            'dp' => $page,
            't' => 'pageview',
            'cs' => $source,
            'cn' => $campaign,
            'cm' => $medium,
            'cid' => $this->client_id,
            'tid' => $this->account,
            'v' => 1
        ));
        $this->tracker->setCampaignSource($source);
        $this->tracker->setCampaignName($campaign);
        $this->tracker->setCampaignMedium($medium);
        $this->tracker->trackPageView($page);
    }

    public function events()
    {
        return array(
            array('Account', 'Logged In', 'Manual', 0),
            array('COnsumption', 'Movie Played', 'Django', 10)
        );
    }

    public function campaigns()
    {
        return array(
            array('utorrrent.eu', 'Torrentz', 'referal'),
            array('koko.lol', 'Cocorico', 'referal'),
        );
    }
}
