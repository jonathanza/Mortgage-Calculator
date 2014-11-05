<?php

/**
 * Calculates the total cost of a mortgage (interest and principal)
 * over its entire duration
 */
class TotalCost implements CalculatorOperationsInterface {

	protected $payment;

	public function __construct(MonthlyPayment $payment){
		$this->payment = $payment;
	}

	public function evaluate($principal, $rate, $months, $args = array()) {
        $args_default = array(
            'interest_only' => false,
            'format_output' => false,

            'precision'     => 2,
            'dec_point'     => localeconv()['decimal_point'],
            'thousands_sep' => localeconv()['thousands_sep'],
        );

        $args = array_merge($args_default, $args);

		$payment = $this->payment->evaluate($principal, $rate, $months);

		if($args['interest_only']) {
			$total_cost = ($payment * ($months)) + $principal;
		}

		$total_cost = $payment * $months;

        if ($args['format_output']) {
            return $this->formatOutput($total_cost, $args);
        }
        else {
            return $total_cost;
        }
	}

    private function formatOutput($amount, $args) {
        return number_format($amount, $args['precision'], $args['dec_point'], $args['thousands_sep']);
    }
}