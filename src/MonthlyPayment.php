<?php

/**
 * Calculates a monthly payment for a mortgage
 */
class MonthlyPayment implements CalculatorOperationsInterface {
    protected $args_default;

    function __construct() {
        $localeconv = localeconv();

        $this->args_default = array(
            'interest_only' => false,
            'format_output' => false,

            'precision'     => 2,
            'dec_point'     => $localeconv['decimal_point'],
            'thousands_sep' => $localeconv['thousands_sep'],
        );
    }

	public function evaluate($principal, $rate, $months, $args = array()) {
        $args = array_merge($this->args_default, $args);

		$rate = $rate/100/12;

		if($args['interest_only']) {
			$payment = $principal * $rate;
		}

		$payment =  ($principal * $rate) / (1 - (pow( 1 + $rate, -1 * $months )));

        if ($args['format_output']) {
            return $this->formatOutput($payment, $args);
        }
        else {
            return $payment;
        }
	}

    public function formatOutput($input, $args) {
        return number_format($input, $args['precision'], $args['dec_point'], $args['thousands_sep']);
    }
}