<?php

namespace Converter;

use Converter\Exception\RateNotFoundException;

interface RateProviderInterface
{
    /**
     * @param Currency $from
     * @param Currency $to
     * @param \DateTime $date
     * @return float
     * @throws RateNotFoundException
     */
    public function getConversionRate(Currency $from,Currency $to,\DateTime $date);
}