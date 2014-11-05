<?php

/**
 * Calculates the total interest cost of a mortgage over its entire duration
 */
class TotalInterest implements CalculatorOperationsInterface {
	protected $totalCost;

	public function __construct(TotalCost $totalCost){
		$this->totalCost = $totalCost;
	}

	public function evaluate($principal, $rate, $months, $args = array()){
        $args_default = array(
            'interest_only' => false,
            'format_output' => false,

            'precision'     => 2,
            'dec_point'     => localeconv()['decimal_point'],
            'thousands_sep' => localeconv()['thousands_sep'],
        );

        $args = array_merge($args_default, $args);

		$total_interest = $this->totalCost->evaluate($principal, $rate, $months) - $principal;

        if ($args['format_output']) {
            return $this->formatOutput($total_interest, $args);
        }
        else {
            return $total_interest;
        }
	}

    private function formatOutput($amount, $args) {
        return number_format($amount, $args['precision'], $args['dec_point'], $args['thousands_sep']);
    }
}