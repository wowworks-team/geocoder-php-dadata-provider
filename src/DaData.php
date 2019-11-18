<?php

namespace Dadata;

use Dadata\Model\DaDataAddress;
use DadataSuggestions\DadataSuggestionsService;
use DadataSuggestions\Response;
use Geocoder\Collection;
use Geocoder\Model\AddressBuilder;
use Geocoder\Model\AddressCollection;
use Geocoder\Provider\Provider;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;

/**
 * Class DaData
 * @package Dadata
 */
class DaData implements Provider
{
    /**
     * @var DadataSuggestionsService
     */
    protected $dadataSuggestionsService;

    /**
     * @param DadataSuggestionsService $dadataSuggestionsService
     */
    public function __construct(DadataSuggestionsService $dadataSuggestionsService)
    {
        $this->dadataSuggestionsService = $dadataSuggestionsService;
    }

    /**
     * {@inheritdoc}
     */
    public function geocodeQuery(GeocodeQuery $query): Collection
    {
        $address = $query->getText();

        return $this->executeQuery($address, $query->getLimit(), $query->getLocale());
    }

    /**
     * {@inheritdoc}
     */
    public function reverseQuery(ReverseQuery $query): Collection
    {
        return new AddressCollection([]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'dadata';
    }

    /**
     * @param string $address
     * @param int $limit
     * @param string $locale
     *
     * @return AddressCollection
     */
    private function executeQuery(string $address, int $limit, string $locale = null): AddressCollection
    {
        $response = $this->dadataSuggestionsService->suggestAddress($address, $limit);
        $status = $response->getStatus();
        $data = $response->getSuggestions();

        /**
         * $data example
         *
         * Array
        (
        [0] => DadataSuggestions\Suggestion Object
        (
        [value:protected] => г Самара, ул Скляренко, влд 26
        [unrestricted_value:protected] => Самарская обл, г Самара, ул Скляренко, влд 26
        [data:protected] => DadataSuggestions\Data\Address Object
        (
        [postal_code] => 443086
        [country] => Россия
        [country_iso_code] => RU
        [federal_district] => Приволжский
        [region_fias_id] => df3d7359-afa9-4aaa-8ff9-197e73906b1c
        [region_kladr_id] => 6300000000000
        [region_iso_code] => RU-SAM
        [region_with_type] => Самарская обл
        [region_type] => обл
        [region_type_full] => область
        [region] => Самарская
        [area_fias_id] =>
        [area_kladr_id] =>
        [area_with_type] =>
        [area_type] =>
        [area_type_full] =>
        [area] =>
        [city_fias_id] => bb035cc3-1dc2-4627-9d25-a1bf2d4b936b
        [city_kladr_id] => 6300000100000
        [city_with_type] => г Самара
        [city_type] => г
        [city_type_full] => город
        [city] => Самара
        [city_area] =>
        [city_district_fias_id] =>
        [city_district_kladr_id] =>
        [city_district_with_type] =>
        [city_district_type] =>
        [city_district_type_full] =>
        [city_district] =>
        [settlement_fias_id] =>
        [settlement_kladr_id] =>
        [settlement_with_type] =>
        [settlement_type] =>
        [settlement_type_full] =>
        [settlement] =>
        [street_fias_id] => 9dcfaa1d-9f6b-4d97-a0f0-b59efe3dcce5
        [street_kladr_id] => 63000001000080100
        [street_with_type] => ул Скляренко
        [street_type] => ул
        [street_type_full] => улица
        [street] => Скляренко
        [house_fias_id] => fc8c2255-9578-4dfd-af69-0d4c6da8e366
        [house_kladr_id] => 6300000100008010044
        [house_type] => влд
        [house_type_full] => владение
        [house] => 26
        [block_type] =>
        [block_type_full] =>
        [block] =>
        [flat_type] =>
        [flat_type_full] =>
        [flat] =>
        [flat_area] =>
        [square_meter_price] =>
        [flat_price] =>
        [postal_box] =>
        [fias_id] => fc8c2255-9578-4dfd-af69-0d4c6da8e366
        [fias_code] => 63000001000000008010044
        [fias_level] => 8
        [fias_actuality_state] => 0
        [kladr_id] => 6300000100008010044
        [geoname_id] =>
        [capital_marker] => 2
        [okato] => 36401000000
        [oktmo] => 36701000
        [tax_office] => 6316
        [tax_office_legal] => 6316
        [timezone] =>
        [geo_lat] => 53.2165853
        [geo_lon] => 50.1609078
        [beltway_hit] =>
        [beltway_distance] =>
        [metro] =>
        [qc_geo] => 0
        [qc_complete] =>
        [qc_house] =>
        [history_values] =>
        [unparsed_parts] =>
        [source] =>
        [qc] =>
        )
        )
        )
         */

        if ($status !== Response::STATUS_SUCCESS) {
            return new AddressCollection([]);
        }

        $locations = [];
        foreach ($data as $item) {
            $builder = new AddressBuilder($this->getName());

            $addressInfo = $item->getData();

            $builder->setCoordinates((float)$addressInfo->geo_lat, (float)$addressInfo->geo_lon);

            $builder->setStreetNumber($addressInfo->house ?? null);
            $builder->setStreetName($addressInfo->street ?? null);
            $builder->setSubLocality($addressInfo->city_with_type ?? null);
            $builder->setLocality($addressInfo->region_with_type ?? null);
            $builder->setCountry($addressInfo->country ?? null);
            $builder->setCountryCode($addressInfo->country_iso_code ?? null);

            /** @var DaDataAddress $location */
            $location = $builder->build(DaDataAddress::class);
            $locations[] = $location;
        }

        return new AddressCollection($locations);
    }
}
