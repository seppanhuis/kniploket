<?php

// Een simpele functie die berekent of een afspraak binnen de openingstijden valt (bijv. tussen 09:00 en 18:00)
function isGeldigeAfspraakTijd($startTijd, $eindTijd) {
    $openingsTijd = '09:00';
    $sluitingsTijd = '18:00';

    if ($startTijd < $openingsTijd || $eindTijd > $sluitingsTijd) {
        return false;
    }
    
    return $startTijd < $eindTijd;
}

describe('Afspraak Tijd Validatie', function () {

    test('een afspraak binnen openingstijden is geldig', function () {
        $resultaat = isGeldigeAfspraakTijd('10:00', '10:30');
        
        expect($resultaat)->toBeTrue();
    });

    test('een afspraak voor openingstijd is ongeldig', function () {
        $resultaat = isGeldigeAfspraakTijd('08:30', '09:30');
        
        expect($resultaat)->toBeFalse();
    });

    test('een afspraak na sluitingstijd is ongeldig', function () {
        $resultaat = isGeldigeAfspraakTijd('17:45', '18:15');
        
        expect($resultaat)->toBeFalse();
    });

});