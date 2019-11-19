<?php

class DaDataTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Dadata\DaData | \PHPUnit\Framework\MockObject\MockObject
     */
    private $daData;

    public function setUp(): void
    {
        parent::setUp();
        $this->daData = $this->createMock(\Dadata\DaData::class);
    }

    public function testGeocodeQuery()
    {

        $this->assertInstanceOf(\Dadata\DaData::class, $this->daData);
        $geocodeQuery = \Geocoder\Query\GeocodeQuery::create('г Москва, улица Академика Королева, дом 15, корп. 2');

        $data = $this->daData->method('geocodeQuery')
            ->with($geocodeQuery)
            ->will($this->returnValue(\Geocoder\Collection::class));


        $this->assertIsObject($data);
    }
}
