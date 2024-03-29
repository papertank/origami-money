<?php

namespace Origami\Money\Formatter;

use Money\Currencies;

class MoneyWithoutTrailingZeros
{
    /**
     * @var \NumberFormatter
     */
    private $formatter;

    /**
     * @var Currencies
     */
    private $currencies;

    /**
     * @param  \NumberFormatter  $formatter
     * @param  Currencies  $currencies
     */
    public function __construct(\NumberFormatter $formatter, Currencies $currencies)
    {
        $this->formatter = $formatter;
        $this->currencies = $currencies;
    }

    public function format($money)
    {
        $valueBase = $money->getAmount();
        $negative = false;

        if ($valueBase[0] === '-') {
            $negative = true;
            $valueBase = substr($valueBase, 1);
        }

        $this->formatter->setPattern('¤#,##0.00');

        $subunit = $this->currencies->subunitFor($money->getCurrency());
        $valueLength = strlen($valueBase);

        if ($valueLength > $subunit) {
            $formatted = substr($valueBase, 0, $valueLength - $subunit);
            $decimalDigits = substr($valueBase, $valueLength - $subunit);

            if (intval($decimalDigits) == 0) {
                $this->formatter->setPattern('¤#,##0.##');
            }

            if (strlen($decimalDigits) > 0) {
                $formatted .= '.'.$decimalDigits;
            }
        } else {
            $formatted = '0.'.str_pad('', $subunit - $valueLength, '0').$valueBase;
        }

        if ($negative === true) {
            $formatted = '-'.$formatted;
        }

        return $this->formatter->formatCurrency($formatted, $money->getCurrency()->getCode());
    }
}
