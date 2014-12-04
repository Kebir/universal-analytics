[![Build Status](https://travis-ci.org/Kebir/universal-analytics.svg?branch=master)](https://travis-ci.org/Kebir/universal-analytics)

#Universal Analytics
--------------------

A package allowing to track Google Analytics Events server-side.

Installation
--
Add the package to your composer.json:
```js
{
    require: {
        "kebir/universal-analytics": "~2.*"
    }
}
```

Usage
--

```php
<?php
use Kebir\UniversalAnalytics\UniversalAnalyticsTracker as Tracker;
use Kebir\UniversalAnalytics\PixelRequest;

//Create a tracker instance
$tracker = new Tracker(new PixelRequest());

//Set the google analytics account.
$tracker->setAccount($account);

//Set the client id of the visitor.
$tracker->setClientId($client_id);

//Set custom dimensions or metrics
$tracker->setCustomDimension(1, "Member");
$tracker->setCustomMetric(2, 1);

//You can also set other informations
$tracker->setCampaignSource("CampaignSource");
$tracker->setCampaignName("CampaignName");
$tracker->setCampaignMedium("CampaignMedium");
$tracker->setCampaignKeyword("CampaignKeyword");
$tracker->setCampaignContent("CampaignContent");
$tracker->setGoogleAdwordsId("GoogleAdwordsId");
$tracker->setGoogleDisplayAdsId("GoogleDisplayAdsId");
$tracker->setIp("Ip");
$tracker->setUserAgent("UserAgent");
$tracker->setUserLanguage("UserLanguage");
$tracker->setNonInteraction("NonInteraction");
$tracker->setExperimentId("ExperimentId");
$tracker->setExperimentVariation("ExperimentVariation");

//To track a pageview
$tracker->trackPageview('/page');

//To track an event
$tracker->trackEvent('General Events', 'Click', 'Facebook Link');

//To track a transaction
//First we have to create the items
$items = array(
    Tracker::createItem('Product Name', 10.99, 2)
);
$tracker->trackTransaction($items, 'transaction_id', 10.99, 'My website');
```
