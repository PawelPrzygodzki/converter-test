<?php

namespace Converter;

use Converter\Exception\InvalidArgumentException;
use Converter\Exception\RateNotFoundException;

class Converter
{
    const ROUND_UP_STRATEGY = 1;
    const ROUND_DOWN_STRATEGY = 2;

    /**
     * @var RateProviderInterface
     */
    protected $rateProvider;

    /**
     * Converter constructor
     * @param RateProviderInterface $rateProvider
     */
    public function __construct(RateProviderInterface $rateProvider)
    {
        $this->rateProvider = $rateProvider;
    }

    /**
     * @param Amount $amount
     * @param Currency $toCurrency
     * @param int $strategy
     * @return Amount
     * @throws InvalidArgumentException
     * @throws RateNotFoundException
     */
    public function convert(Amount $amount, Currency $toCurrency, $strategy = self::ROUND_UP_STRATEGY)
    {
        $rate = $this->rateProvider->getConversionRate($amount->getCurrency(), $toCurrency, new \DateTime());

        $convertedValue = $amount->getValue() * $rate;
        $convertedValue = $this->round($convertedValue, $strategy);

        return new Amount($convertedValue, $toCurrency);
    }

    /**
     * @param int $value
     * @param int $strategy
     * @return float
     * @throws InvalidArgumentException
     */
    protected function round($value, $strategy)
    {
        switch ($strategy) {
            case self::ROUND_UP_STRATEGY:
                return ceil($value * 100) / 100;
                break;
            case  self::ROUND_DOWN_STRATEGY:
                return floor($value * 100) / 100;
                break;
            default:
                throw new InvalidArgumentException('Round strategy value is not supported');
                break;
        }
    }
}