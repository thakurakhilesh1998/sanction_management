<?php
function addCommas($number) 
{
    $number = (int)$number;
    $formatter = new NumberFormatter('en_IN', NumberFormatter::CURRENCY);
    $formattedNumber = $formatter->formatCurrency($number, 'INR');
    return $formattedNumber;
}
 ?>