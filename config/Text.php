<?php
/**
 * Remco Schipper
 * Date: 12/12/14
 * Time: 11:18
 */

namespace config;


class Text {
    public static $values = array(
        'coupons' => array(
            'validation' => array(
                'code' => 'De code moet tussen de 1 en 45 tekens lang zijn',
                'description' => 'De beschrijving moet tussen de 1 en 45 tekens lang zijn',
                'valid_until' => 'Moet een geldige datum en tijd zijn, bijvoorbeeld: "%"',
                'fixed_amount' => 'Het kortingsbedrag moet een nummer zijn, bijvoorbeeld 2,50',
                'percentage' => 'Het kortingspercentage moet een nummer zijn, bijvoorbeeld "2" of "3"',
                'amount' => 'Het aantal keer dat de coupon gebruikt mag worden moet een heel getal zijn',
                'double' => 'Er bestaat al een kortingscode met deze code'
            ),
            'use' => array(
                'success' => '<div class="alert alert-success">De kortingscode is succesvol toegepast</div>',
                'error' => '<div class="alert alert-danger">De kortingscode kon niet gebruikt worden</div>',
            )
        ),
        'emails' => array(
            'validation' => array(
                'subject' => 'Een onderwerp is verplicht',
                'reply_to' => 'Het is verplicht om een antwoord adres in te vullen',
                'reply_to_name' => 'De weergaven naam voor het antwoord adres is verplicht',
                'option' => 'Het is verplicht een optie te selecteren',
                'body' => 'De tekst moet langer dan 1 teken zijn',
                'body_html' => 'De opmaak versie van de e-mail is verplicht',
                'double' => 'Er is al een e-mail template gekoppeld aan deze actie'
            )
        ),
        'paymentmethods' => array(
            'validation' => array(
                'name' => 'De naam moet tussen de 1 en 100 tekens lang zijn',
                'description' => 'Beschrijving moet 1 tot 9000 tekens zijn',
                'double' => 'Er is al een betaalmethode met deze naam'
            )
        ),
        'shippingmethods' => array(
            'validation' => array(
                'name' => 'De naam moet tussen de 1 en 100 tekens lang zijn',
                'double' => 'Er bestaat al een verzendmethode met deze naam'
            )
        ),
        'statuses' => array(
            'validation' => array(
                'description' => 'De beschrijving moet tussen de 1 en 255 tekens lang zijn',
                'double' => 'Er bestaat al een bestelstatus met deze beschrijving'
            )
        ),
        'settings' => array(
            'validation' => array(
              'price' => 'De prijs moet een getal zijn',
              'btw' => 'Het btw percentage moet een getal zijn',
              'iban' => 'Iban nummer moet 18 tekens lang zijn'
            ),
            'starting_cost' => array(
                'results' => array(
                    'error' => '<div class="alert alert-danger">Kon door een server fout de opstartkosten niet bijwerken</div>',
                    'success' => '<div class="alert alert-success">De opstartkosten zijn bijgewerkt</div>'
                )
            ),
            'btw_percentage' => array(
                'results' => array(
                    'error' => '<div class="alert alert-danger">Kon door een server fout het btw percentage niet bijwerken</div>',
                    'success' => '<div class="alert alert-success">Het btw percentage is bijgewerkt</div>'
                )
            ),
            'iban_number' => array(
                'results' => array(
                    'error' => '<div class="alert alert-danger">Kon door een server fout het IBAN nummer niet bijwerken</div>',
                    'success' => '<div class="alert alert-success">Het IBAN nummer is bijgewerkt</div>'
                )
            )
        ),
        'global' => array(
            'mandatory' => '<p class="text-muted">Een veld gemarkeerd met (<span class="mandatory">*</span>) is verplicht.</p>',
            'remove' => array(
                'confirm' => 'Weet je zeker dat je de %s wilt verwijderen?',
                'error' => 'Kon de %s niet verwijderen, zijn er bestellingen aan verbonden?'
            ),
            'results' => array(
                'create' => array(
                    'error' => '<div class="alert alert-danger">Kon door een server fout de %s niet maken</div>',
                    'success' => '<div class="alert alert-success">De %s (<a href="%s">%s</a>) is toegevoegd, klik <a href="%s">hier</a> om terug te gaan</div>'
                ),
                'edit' => array(
                    'error' => '<div class="alert alert-danger">Kon door een server fout de %s niet bewerken</div>',
                    'success' => '<div class="alert alert-success">De %s (<a href="%s">%s</a>) is bewerkt, klik <a href="%s">hier</a> om terug te gaan</div>'
                )
            )
        )
    );
} 