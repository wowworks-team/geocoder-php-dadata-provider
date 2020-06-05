<?php

namespace Wowworks\Dadata\Model;

use Geocoder\Model\Address;
use Geocoder\Provider\Yandex\Model\YandexAddress;

/**
 * Class DaDataAddress
 * @package common\components\dadata\Model
 */
class DaDataAddress extends Address
{
    /*
https://dadata.ru/suggestions/usage/address/
0 — точные координаты
1 — ближайший дом
2 — улица
3 — населенный пункт
4 — город
5 — координаты не определены
     */
    const QC_GEO_ACCURATE = 0;
    const QC_GEO_HOUSE = 1;
    const QC_GEO_STREET = 2;
    const QC_GEO_SETTLEMENT = 3;
    const QC_GEO_CITY = 4;
    const QC_GEO_UNKNOWN = 5;

    /**
     * @var int|null
     */
    private $precision;

    /**
     * See https://dadata.ru/suggestions/usage/address/ data.qc_geo
     *
     * @return null|int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param null|int $precision
     *
     * @return DaDataAddress
     */
    public function withPrecision(int $precision = null): self
    {
        $new = clone $this;
        $new->precision = $precision;

        return $new;
    }
}
