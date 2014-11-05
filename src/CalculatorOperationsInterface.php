<?php

/**
 * Implementation for all of the calculator functions
 */
interface CalculatorOperationsInterface{
	public function evaluate($principal, $rate, $months, $args = array());

    public function formatOutput($input, $args);
}