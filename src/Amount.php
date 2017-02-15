<?php

namespace Converter;

use Converter\Exception\CurrencyNotSupportedException;
use Converter\Exception\InvalidArgumentException;

class Amount
{
    /**
     * @var int
     */
    protected $value;

    /**
     * @var Currency
     */
    protected $currency;

    /**
     * Amount constructor.
     * @param int $value
     * @param Currency $currency
     */
    public function __construct($value, Currency $currency)
    {
        $this->setValue($value);
        $this->setCurrency($currency);
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    protected function setValue($value)
    {
        if (!is_numeric($value)) {
            throw new InvalidArgumentException('Value of amount must be numeric');
        }

        $this->value = (float) $value;
    }

    protected function setCurrency(Currency $currency)
    {
        $this->currency = $currency;
    }
}