<?php


class DaDataTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \DadataSuggestions\DadataSuggestionsService
     */
    private $service;

    /**
     * @var \Dadata\DaData
     */
    private $daData;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->getService();
        $this->daData = $this->getDaData($this->service);
    }


    public function testDaDataTypes()
    {
        $data = $this->daData->geocodeQuery(\Geocoder\Query\GeocodeQuery::create('Moscow'));

        $this->assertInstanceOf(\Geocoder\Model\AddressCollection::class, $data);

        foreach ($data as $key => $item) {
            $this->assertInstanceOf(\Dadata\Model\DaDataAddress::class, $item);
        }
    }

    public function testDaData()
    {
        $query = \Geocoder\Query\GeocodeQuery::create('г Москва, улица Академика Королева, дом 15, корп. 2');
        $data = $this->daData->geocodeQuery($query);

        $this->assertNotEmpty($data);

        $daDataAddres = array_pop($data->all());

        $this->assertNotEmpty($daDataAddres->getCoordinates());

        $this->assertEquals($daDataAddres->getStreetName(), 'Академика Королева');

        $this->assertEquals($daDataAddres->getStreetNumber(), 15);

        $this->assertEquals($daDataAddres->getSubLocality(), 'г Москва');

        $this->assertEquals($daDataAddres->getLocality(), 'г Москва');

        $this->assertObjectHasAttribute('name', $daDataAddres->getCountry());

        $this->assertObjectHasAttribute('code', $daDataAddres->getCountry());

    }

    /**
     * @return \DadataSuggestions\DadataSuggestionsService
     */
    private function getService()
    {
        $service = new \DadataSuggestions\DadataSuggestionsService();
        $service->setToken('ab626ac37b47868748ea2f408293ed6a1e420944');
        return $service;
    }

    /**
     * @param \DadataSuggestions\DadataSuggestionsService $service
     * @return \Dadata\DaData
     */
    private function getDaData(\DadataSuggestions\DadataSuggestionsService $service)
    {
        return new \Dadata\DaData($service);
    }
}