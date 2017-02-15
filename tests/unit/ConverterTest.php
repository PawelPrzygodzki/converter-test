<?php

namespace Test\Unit;

use Converter\Amount;
use Converter\Converter;
use Converter\Currency;
use Converter\Exception\InvalidArgumentException;
use Converter\Exception\RateNotFoundException;
use Converter\RateProviderInterface;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    protected $rateProviderMock;

    /**
     * @var Converter
     */
    protected $converter;

    public function setUp()
    {
        $this->rateProviderMock = $this->getMock(RateProviderInterface::class);
        $this->converter = new Converter($this->rateProviderMock);
    }

    public function testShouldRoundUpResultDefault()
    {
        //given correct amount with currency
        $amount = new Amount('12.99', Currency::PLN());
        //and expected currency
        $toCurrency = Currency::EUR();
        //external rate provider get correct rate
        $rate = 0.23;
        $this->rateProviderMock
            ->method('getConversionRate')
            ->with(Currency::PLN, Currency::EUR)
            ->will($this->returnValue($rate));

        // when convert amount from current currency to expected currency
        // round strategy is not set
        $result = $this->converter->convert($amount, $toCurrency);

        //then result is instance of Amount
        $this->assertInstanceOf(Amount::class, $result);
        //and amount value is correct(round up)
        $this->assertSame(2.99, $result->getValue());
        //and amount currency is correct
        $this->assertSame($toCurrency, $result->getCurrency());
    }

    public function testShouldRoundDownResultForRoundDownStrategy()
    {
        //given correct amount with currency
        $amount = new Amount('12.99', Currency::PLN());
        //and expected currency
        $toCurrency = Currency::EUR();
        //and round strategy is set on round down
        $roundStrategy = Converter::ROUND_DOWN_STRATEGY;

        //external rate provider get correct rate
        $rate = 0.23;
        $this->rateProviderMock
            ->method('getConversionRate')
            ->with(Currency::PLN, Currency::EUR)
            ->will($this->returnValue($rate));

        // when convert amount from current currency to expected currency
        $result = $this->converter->convert($amount, $toCurrency, $roundStrategy);

        //then result is instance of Amount
        $this->assertInstanceOf(Amount::class, $result);
        //and amount value is correct(round down)
        $this->assertSame(2.98, $result->getValue());
        //and amount currency is correct
        $this->assertSame($toCurrency, $result->getCurrency());
    }

    public function testShouldReturnErrorForIncorrectRoundStrategy()
    {
        //given correct amount with currency
        $amount = new Amount('12.99', Currency::PLN());
        //and expected currency
        $toCurrency = Currency::EUR();
        //and round strategy has set incorrect value
        $roundStrategy = 666;
        //external rate provider get correct rate
        $rate = 0.23;
        $this->rateProviderMock
            ->method('getConversionRate')
            ->with(Currency::PLN, Currency::EUR)
            ->will($this->returnValue($rate));

        //expected converter return invalid argument error
        $this->setExpectedException(InvalidArgumentException::class);

        // when convert amount from current currency to expected currency
        $this->converter->convert($amount, $toCurrency, $roundStrategy);
    }

    public function testShouldReturnErrorWhenProviderNotFoundRate()
    {
        //given correct amount with currency
        $amount = new Amount('12.99', Currency::PLN());
        //and expected currency
        $toCurrency = Currency::EUR();
        //and round strategy is set correct
        $roundStrategy = Converter::ROUND_DOWN_STRATEGY;
        //external rate provider get correct rate
        $rate = 0.23;
        $this->rateProviderMock
            ->method('getConversionRate')
            ->with(Currency::PLN, Currency::EUR)
            ->will($this->throwException(new RateNotFoundException('Rate not found for this currency')));

        //expected converter return rate not found error
        $this->setExpectedException(RateNotFoundException::class);

        // when convert amount from current currency to expected currency
        $this->converter->convert($amount, $toCurrency, $roundStrategy);
    }
}
