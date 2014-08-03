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
        "kebir/universal-analytics": "1.*"
    }
}
```

Usage
--

```php
<?php
use Kebir\UniversalAnalytics\UniversalAnalyticsTracker as Tracker;
use Kebir\UniversalAnalytics\PixelRequest;

//First we have to create a tracker instance
$tracker = new Tracker('UA-xxxxxxxx-y', 'visitor_id', new PixelRequest());

//The first parameter is the account id in google analytics
//The second parameter is an identifier for the visitor (you can generate your own)
//The third parameter is a simple wrapper for curl


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
