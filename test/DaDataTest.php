<?php

use DadataSuggestions\DadataSuggestionsService;
use Dadata\DaData;
use DadataSuggestions\Response;
use Geocoder\Model\AddressCollection;
use Geocoder\Query\ReverseQuery;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Model\Coordinates;
use Dadata\Exception\NotImplementedException;

class DaDataTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var DaData
     */
    private $daData;

    /**
     * @var DadataSuggestionsService
     */
    private $dadataSuggestionsService;

    /**
     * @var Response
     */
    private $responseDadataSuggestion;

    public function setUp(): void
    {
        parent::setUp();
        $this->dadataSuggestionsService = $this->getMockBuilder(DadataSuggestionsService::class)->getMock();
        $this->responseDadataSuggestion = $this->createMock(Response::class);
        $this->daData = new DaData($this->dadataSuggestionsService);
    }

    public function testGeocodeQuery()
    {
        $this->dadataSuggestionsService->expects($this->once())
            ->method('suggestAddress')
            ->willReturn($this->responseDadataSuggestion);

        $geocodeQuery = GeocodeQuery::create('г Москва, улица Академика Королева, дом 15, корп. 2');
        $data = $this->daData->geocodeQuery($geocodeQuery);

        $this->assertIsObject($data);
        $this->assertInstanceOf(AddressCollection::class, $data);
    }

    public function testReverseQuery()
    {
        $this->expectException(NotImplementedException::class);
        $this->daData->reverseQuery(ReverseQuery::create(new Coordinates(85.4, 87.3)));
    }

    public function testGetName()
    {
        $this->assertIsString($this->daData->getName());
        $this->assertEquals($this->daData->getName(), 'dadata');
    }
}
