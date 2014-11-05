<?php

/**
 * Calculates the total interest cost of a mortgage over its entire duration
 */
class TotalInterest implements CalculatorOperationsInterface {

	protected $totalCost;
    protected $args_default;

	public function __construct(TotalCost $totalCost){
		$this->totalCost = $totalCost;

        $this->args_default = array(
            'interest_only' => false,
            'format_output' => false,

            'precision'     => 2,
            'dec_point'     => localeconv()['decimal_point'],
            'thousands_sep' => localeconv()['thousands_sep'],
        );
	}

	public function evaluate($principal, $rate, $months, $args = array()){
        $args = array_merge($this->args_default, $args);

		$total_interest = $this->totalCost->evaluate($principal, $rate, $months) - $principal;

        if ($args['format_output']) {
            return $this->formatOutput($total_interest, $args);
        }
        else {
            return $total_interest;
        }
	}

    public function formatOutput($input, $args) {
        return number_format($input, $args['precision'], $args['dec_point'], $args['thousands_sep']);
    }
}