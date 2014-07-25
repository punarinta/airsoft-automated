<?php

return array
(
    'menu' => array
    (
        'home' => 'Hem',
        'games' => 'Spel',
        'i_play' => 'Du spelar',
        'i_organize' => 'Du arrangerar',
        'profile' => 'Profil',
        'about' => 'Om oss',
        'sign_in' => 'Logga in',
        'sign_out' => 'Logga ut',
        'no_nick' => 'inget nick',
    ),
    'util' => array
    (
        'any-country' => 'Alla länder',
        'any-region' => 'Alla område',
        'any-team' => 'Alla lag',
        'do-view' => 'titta på',
        'do-edit' => 'redigera',
        'do-book' => 'boka',
    ),
    'home' => array
    (
        'title' => 'Hem',
        'non-email' => 'Tyvärr är det inte ett email.',
        'check-email' => 'Tack. Kolla ditt email för ett lösenord.',
        'sign-up' => 'Registrera dig nu!',
        'dash-org' => 'Arrangörens panel',
        'dash-player' => 'Spelarens panel',
        'ad' => array
        (
            'o0' => 'För arrangörer',
            'o1' => 'Skapa ett spel i ett par klick',
            'o2' => 'Bryr dig inte om biljetter',
            'o3' => 'Bryr dig inte om att informera alla',
            'o4' => 'Spara tid med automatiska incheckningen',
            'o5' => 'Analysera statistik',
            'p0' => 'För spelare',
            'p1' => 'Hantera ditt lag',
            'p2' => 'Boka biljetter säkert',
            'p3' => 'Betala med kontant eller trådlöst',
            'p4' => 'Få logistik via e-post',
            'p5' => 'Njut av automatiska incheckningen',
        ),
    ),
    'profile' => array
    (
        'title' => 'Din profil',
        'head-status' => 'Status',
        'head-profile' => 'Din profil',
        'head-team' => 'Ditt lag',
        'stat' => array
        (
            '1a' => 'Du har klarat validering och kan arrangöra spel.',
            '1b' => 'Du kan inte arrangöra spel. <a href="%s">Läs här hur du kan validera dig själv.</a>',
            '2a' => 'Du har klarat e-validering och kan delta i spel.',
            '2b' => 'Du kan inte delta i spel eller skapa ett lag. Kolla ditt mail för en bekräftelselänk.',
            '3a' => 'Du kan redigera ditt lag eftersom det var du som lagt till det i systemet.',
            '3b' => 'Du kan inte redigera ditt lag, eftersom det var inte du som skapade det.',
            '3c' => 'Du får skapa ditt lag eller gå med i det befintliga.',
        ),
        'you-nick' => 'Nick',
        'you-team' => 'Lag',
        'you-lang' => 'Språk',
        'you-bday' => 'Födelsedag',
        'you-phone' => 'Telefon',
        'you-org-area' => 'Dessa kan krävas av arrangörerna',
        'team-name' => 'Namn',
        'team-location' => 'Område',
        'team-url' => 'URL',
        'team-region' => 'Område',
        'save-profile' => 'Spara profilen',
        'save-team' => 'Spara laget',
        'create-team' => 'Spara ett lag',
        'not-team-owner' => 'Endast lag skapare kan redigera laget',
        'check-team' => 'Vi rekommenderar dig att kontrollera att ditt lag inte finns i systemet. Har du gjort det?',
    ),
    'games' => array
    (
        'title' => 'Spel',
        'head-date' => 'Datum',
        'head-name' => 'Spel',
        'head-arranger' => 'Arranged by',
        'head-region' => 'Område',
        'head-booking' => 'Bokning',
        'no-tickets' => 'inga bijetter',
        'info-booked' => 'bokad',
        'add-own' => 'Skapa ditt spel!',
    ),
    'player-dash' => array
    (
        'title' => 'Deltagarens panel',
        'empty-message' => 'Du deltar inte i något spel. Gå gärna till <a href="%s">spel lista</a> för att boka biljetter.',
        'head-date' => 'Datum',
        'head-name' => 'Namn',
        'head-region' => 'Område',
        'head-logistics' => 'Logistik',
    ),
    'validate-org' => array
    (
        'title' => 'Validera dig',
        'text' => '<p>För att skydda spelare och deras ekonomi måste vi validera alla spelarrangörer.</p>
        <p>Skriv gärna ett par ord om dig som en arrangör till <a target="_blank" href="mailto:%s?subject=I am an organizer">%s</a> email och vi ska svara så snart som möjligt. :)</p>
        Systemet är nu i utvecklingsversion, så det finns <b>inga avgifter</b> för hanteringen.',
    ),
    'briefing' => array
    (
        'title' => 'Specifik spelinfo',
        'head-payment' => 'Information om betalning',
        'head-payment-int' => 'Additional info for international players',
        'head-amount' => 'Belopp',
        'head-account' => 'Konto',
        'head-reference' => 'Referens',
        'no-map' => 'Ingen specifik information finns på det här spelet.',
        'not-paid' => 'Din biljett är bokad men inte betald.',
        'print-ticket' => 'Du kan skriva ut din biljett och du är klar.',
        'do-print-ticket' => 'Skriv ut biljett',
        'person-count' => 'biljett för %d person|biljett för %d personer',
    ),
    'booking' => array
    (
        'title' => 'Biljettbokning',
        'head-requirements' => 'Krav',
        'head-ticket' => 'Välj en biljett',
        'head-options' => 'Options',
        'head-party' => 'Spelsidan',
        'head-available-tickets' => 'Tillgängliga biljetter',
        'head-persons-count' => 'Antal personer',
        'you-organize' => 'Obs: du organiserar det här spelet',
        'game-info' => 'Du bokar en biljett till spelet «<b>%s</b>» arrangerad av <b>%s</b>.',
        'pick-both' => 'Vänligen plocka både en spelsida och en biljettyp.',
        'do-fill-profile' => 'lägg till i profilen',
    ),
);