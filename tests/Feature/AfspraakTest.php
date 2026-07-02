<?php

test('wiskunde werkt altijd', function () {
    $resultaat = 1 + 1;
    
    expect($resultaat)->toBe(2);
});