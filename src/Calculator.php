<?php

namespace PragmaGoTech\Interview;

use PragmaGoTech\Interview\Exceptions\AmountOutOfRangeException;
use PragmaGoTech\Interview\Model\Loan;

class Calculator implements FeeCalculator
{
    private array $feeStructure = [
        12 => [
            1000 => 50,
            2000 => 90,
            3000 => 90,
            4000 => 115,
            5000 => 100,
            6000 => 120,
            7000 => 140,
            8000 => 160,
            9000 => 180,
            10000 => 200,
            11000 => 220,
            12000 => 240,
            13000 => 260,
            14000 => 280,
            15000 => 300,
            16000 => 320,
            17000 => 340,
            18000 => 360,
            19000 => 380,
            20000 => 400,
        ],
        24 => [
            1000 => 70,
            2000 => 100,
            3000 => 120,
            4000 => 160,
            5000 => 200,
            6000 => 240,
            7000 => 280,
            8000 => 320,
            9000 => 360,
            10000 => 400,
            11000 => 440,
            12000 => 480,
            13000 => 520,
            14000 => 560,
            15000 => 600,
            16000 => 640,
            17000 => 680,
            18000 => 720,
            19000 => 760,
            20000 => 800,
        ]
    ];

    public function __construct(array $feeStructure = null)
    {
        $this->feeStructure = $feeStructure ?? $this->feeStructure;
    }

    public function calculate(Loan $loan): float
    {
        $amount = $loan->amount();
        $term = $loan->term();
        $feeStructure = $this->feeStructure[$term];
        $feeBreakpoints = array_keys($feeStructure);


        //Return exact fee if it exists
        if (in_array($loan->amount(), $feeBreakpoints)) {
            return $feeStructure[$amount];
        }

        //Throw exception if amount is out of range
        if ($amount < min($feeBreakpoints) || $amount > max($feeBreakpoints)) {
            throw new AmountOutOfRangeException;
        }

        $closestBreakpointsToAmount = $this->getClosestBreakpoints($amount, $feeBreakpoints);

        $lowerBreakpoint = min($closestBreakpointsToAmount);
        $upperBreakpoint = max($closestBreakpointsToAmount);

        $lowerFee = $feeStructure[min($closestBreakpointsToAmount)];
        $upperFee = $feeStructure[max($closestBreakpointsToAmount)];

        //Interpolate fee
        $fee = $lowerFee + (($upperFee - $lowerFee) * ($amount - $lowerBreakpoint)) / ($upperBreakpoint - $lowerBreakpoint);

        //Round to nearest multiply  of 5
        return ceil($fee / 5) * 5;
    }


    private function getClosestBreakpoints($amount, $feeAmounts): array
    {
        //There are always 2 items in the array
        //Case 1: Amount is less than the smallest break point - solved by AmountOutOfRangeException
        //Case 2: Amount is greater than the largest break point - solved by AmountOutOfRangeException
        //Case 3: Amount is equal to a break point - solved by returning the exact fee

        $diff = [...array_map(fn($value) => [
            'diff' => abs($value - $amount),
            'value' => $value
        ], $feeAmounts)];

        usort($diff, fn($a, $b) => $a['diff'] <=> $b['diff']);

        return [
            $diff[0]['value'],
            $diff[1]['value']
        ];
    }
}