# geocoder-php-dadata-provider

Geocoder DaData adapter
=================

Integration with Dadata suggestions API.

[![Latest Stable Version](https://poser.pugx.org/wowworks/geocoder-php-dadata-provider/v/stable)](https://packagist.org/packages/wowworks/geocoder-php-dadata-provider)
[![Total Downloads](https://poser.pugx.org/wowworks/geocoder-php-dadata-provider/downloads)](https://packagist.org/packages/wowworks/geocoder-php-dadata-provider)
[![Latest Unstable Version](https://poser.pugx.org/wowworks/geocoder-php-dadata-provider/v/unstable)](https://packagist.org/packages/wowworks/geocoder-php-dadata-provider)
[![License](https://poser.pugx.org/wowworks/geocoder-php-dadata-provider/license)](https://packagist.org/packages/wowworks/geocoder-php-dadata-provider)

Installation
-------------

This extension is available at packagist.org and can be installed via composer by following command:

`composer require wowworks/geocoder-php-dadata-provider`

Configuration
---------
To work, you need to connect the package `wowworks/geocoder-php-dadata-provider`

Example:

```php
$service = new \DadataSuggestions\DadataSuggestionsService();
$service->setUrl('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/');
$service->setToken('...');
$dadataProvider = new \Wowworks\Dadata\Dadata($service);
$collection = $dadataProvider->geocodeQuery(\Geocoder\Query\GeocodeQuery::create('г Москва, улица Академика Королева, дом 15, корп. 2'));
foreach ($collection->all() as $location) {
    $location->getCoordinates()->getLatitude();
    $location->getCoordinates()->getLongitude();

    $location->getCountry();
    foreach ($location->getAdminLevels() as $level) {
        if ($level instanceof AdminLevel) {
            $level->getName();
        }
    }
    $location->getLocality();
    $location->getSubLocality();
    $location->getStreetName();
    $location->getStreetNumber();
    $location->getProvidedBy();
}

```

