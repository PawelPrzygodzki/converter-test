<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 15.02.17
 * Time: 22:09
 */

namespace Test\Unit;


use Converter\Amount;
use Converter\Currency;
use Converter\Exception\InvalidArgumentException;


class AmountTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldReturnErrorWhenAmountValueIsIncorrect()
    {
        //given invalid value for amount
        $value = 'invalid';
        //and correct currency
        $currency = Currency::PLN();

        //expected get error invalid argument
        $this->setExpectedException(InvalidArgumentException::class);

        //when try create amount with this value
        $amount = new Amount($value,$currency);
    }

    /**
     * @param $value
     * @param $currency
     * @dataProvider correctAmountValueProvider
     */
    public function testShouldCreateAmountWhenValueAndCurrencyAreValid($value,$currency)
    {
        //given valid value for amount $value
        //and correct currency $currency


        //when create amount with this value
        $amount = new Amount($value,$currency);

        //then get correct amount
        $this->assertInstanceOf(Amount::class,$amount);
        //and amount has correct value
        $this->assertSame((float) $value,$amount->getValue());
        //and correct currency
        $this->assertSame($currency,$amount->getCurrency());
    }

    /**
     * Provide correct data for amount object
     * @return array
     */
    public function correctAmountValueProvider()
    {
        return [
            [2,Currency::PLN()], // value is set as int, and currency PLN
            [2.30,Currency::EUR()],// value is set as float, and currency EUR
            ['2',Currency::USD()], // value is set as string, and currency USD
            ['2.33',Currency::PLN()]  // value is set as string, and currency PLN
        ];
    }
}
