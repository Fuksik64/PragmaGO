<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview;

use PragmaGoTech\Interview\Model\Loan;

interface FeeCalculator
{
    /**
     * @return float The calculated total fee.
     */
    public function calculate(Loan $loan): float;
}
