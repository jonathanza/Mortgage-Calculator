<?php

/**
 * Generates an amortization schedule for a mortgage detailing each of the payments needed
 */
class Amortization implements CalculatorOperationsInterface{

	protected $payment;
	function __construct(MonthlyPayment $payment){
		$this->payment = $payment;
	}

	public function evaluate($principal, $rate, $months, $args = array()) {
        $args_default = array(
            'interest_only' => false,
            'format_output'	=> false,

            'precision'     => 2,
            'dec_point'		=> localeconv()['decimal_point'],
            'thousands_sep'	=> localeconv()['thousands_sep'],
        );

        $args = array_merge($args_default, $args);

		$amortizationArray = array();

		$monthlyPayment = $this->payment->evaluate($principal, $rate, $months);

		//If the loan is interest-only
		if($args['interest_only']) {
			$i = 1;

			//Generate payments array
			while($i < $months){
				array_push($amortizationArray, array($monthlyPayment, $monthlyPayment, 0, $principal));
				$i++;
			}

			//Last month's payment
			array_push($amortizationArray, array($principal + $monthlyPayment, $monthlyPayment, $principal, 0));
			return $amortizationArray;
		}

		//If the loan is fully amortized
		for($i = 1; $i <= $months; $i++){
			$interestPayOff = ($principal * ($rate / 100) / 12);
			$principalPayOff = $monthlyPayment - $interestPayOff;
			$principal -= $principalPayOff;

			array_push($amortizationArray, array($monthlyPayment, $interestPayOff, $principalPayOff, $principal));
		}

		if ($args['format_output']) {
			return $this->formatOutput($amortizationArray, $args);
		}
		else {
			return $amortizationArray;
		}
	}

    public function formatOutput($input, $args) {
        $output = array();

        foreach ($input as $payment) {
            array_push($output, array(
                number_format($payment[0], $args['precision'], $args['dec_point'], $args['thousands_sep']),
                number_format($payment[1], $args['precision'], $args['dec_point'], $args['thousands_sep']),
                number_format($payment[2], $args['precision'], $args['dec_point'], $args['thousands_sep']),
                number_format($payment[3], $args['precision'], $args['dec_point'], $args['thousands_sep']),
            ));
        }

        return $output;
    }
}
