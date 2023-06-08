<?php

use PragmaGoTech\Interview\Calculator;
use PragmaGoTech\Interview\Model\Loan;

it('can get right fee that match fee structure', function ($term, $amount, $feeResult) {
    $calculator = new Calculator();

    $loan = new Loan($term, $amount);
    $fee = $calculator->calculate($loan);

    expect($fee)->toBe((float)$feeResult);
})->with([
    [12, 1000, 50],
    [12, 2000, 90],
    [24, 1000, 70],
    [24, 2000, 100]
]);

it('can calculate right fee for given loan value', function ($term, $amount, $feeResult) {
    $calculator = new Calculator();

    $loan = new Loan($term, $amount);
    $fee = $calculator->calculate($loan);

    expect($fee)->toBe((float)$feeResult);
})->with([
    [24, 11500, 460],
    [12, 19250, 385],
    [24, 2750, 115],
]);

it('rounds up result to exact multiple of 5', function ($term, $amount, $feeResult) {
    $calculator = new Calculator();

    $loan = new Loan($term, $amount);
    $fee = $calculator->calculate($loan);

    expect($fee)->toBe((float)$feeResult);
})->with([
    [24, 11600, 465],
    [24, 2800, 120],
    [12, 19700, 395]

]);
it('can provide different fee structures', function () {
    $feeStructure=[
        12=>[
            500=>50,
            600=>60,
        ]
    ];
    $calculator = new Calculator($feeStructure);

    $loan = new Loan(12, 550);
    $fee = $calculator->calculate($loan);

    expect($fee)->toBe((float)55);
});