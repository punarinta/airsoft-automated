<?php

return array
(
    'menu' => array
    (
        'home' => 'Home',
        'games' => 'Games',
        'i_play' => 'I play',
        'i_organize' => 'I organize',
        'profile' => 'Profile',
        'about' => 'About',
        'sign_in' => 'Sign in',
        'sign_out' => 'Sign out',
        'no_nick' => 'no nick',
    ),
    'util' => array
    (
        'any-country' => 'Any country',
        'any-region' => 'Any region',
        'any-team' => 'Any team',
        'do-view' => 'view',
        'do-edit' => 'edit',
        'do-book' => 'book',
    ),
    'home' => array
    (
        'title' => 'Home',
        'non-email' => 'Sorry, that´s not an email.',
        'check-email' => 'Thanks. Check your email for a password.',
        'sign-up' => 'Sign-up now!',
        'dash-org' => 'Organizer dashboard',
        'dash-player' => 'Player dashboard',
        'ad' => array
        (
            'o0' => 'For organizers',
            'o1' => 'Create a game in a couple of clicks',
            'o2' => 'Don\'t care about tickets',
            'o3' => 'Don\'t care about informing everyone',
            'o4' => 'Save time with automatic game check-in',
            'o5' => 'Analyse statistics',
            'p0' => 'For players',
            'p1' => 'Manage your team',
            'p2' => 'Safely book tickets',
            'p3' => 'Pay in cash or wireless',
            'p4' => 'Receive all logistics via email',
            'p5' => 'Enjoy automatic game check-in',
        ),
    ),
    'profile' => array
    (
        'title' => 'Your profile',
        'head-status' => 'Status',
        'head-profile' => 'Your profile',
        'head-team' => 'Your team',
        'stat' => array
        (
            '1a' => 'You have passed validation and can organize games.',
            '1b' => 'You cannot organize games. <a href="%s">Read here how to validate yourself.</a>',
            '2a' => 'You have passed email validation and can participate in games.',
            '2b' => 'You cannot participate in games and create a team. Check your email for a email confirmation link.',
            '3a' => 'You can edit your team as it was you who added it to the system.',
            '3b' => 'You cannot edit your team, as you did not create it.',
            '3c' => 'You may create your team or join an existing one.',
        ),
        'you-nick' => 'Nick',
        'you-team' => 'Team',
        'you-lang' => 'Language',
        'you-bday' => 'Birthday',
        'you-phone' => 'Phone',
        'you-org-area' => 'These may be required by organizers',
        'team-name' => 'Name',
        'team-location' => 'Location',
        'team-url' => 'URL',
        'team-region' => 'Region',
        'save-profile' => 'Save profile',
        'save-team' => 'Save team',
        'create-team' => 'Create team',
        'not-team-owner' => 'Only team creator can edit the team',
        'check-team' => 'We recommend you to check that your team does not exist in the system. Have you done it?',
    ),
    'games' => array
    (
        'title' => 'Games',
        'head-date' => 'Date',
        'head-name' => 'Game',
        'head-arranger' => 'Arranged by',
        'head-region' => 'Region',
        'head-booking' => 'Booking',
        'no-tickets' => 'no tickets',
        'info-booked' => 'booked',
        'add-own' => 'Create your own!',
    ),
    'player-dash' => array
    (
        'title' => 'Participant dashboard',
        'empty-message' => 'You are not participating in any game. Please go to <a href="%s">games list</a> to book tickets.',
        'head-date' => 'Date',
        'head-name' => 'Name',
        'head-region' => 'Region',
        'head-logistics' => 'Logistics',
    ),
    'validate-org' => array
    (
        'title' => 'Validate yourself',
        'text' => '<p>To protect players and their finances we need to validate all game organizers.</p>
        <p>Just write a couple of words about you as an organizer on <a target="_blank" href="mailto:%s?subject=I am an organizer">%s</a> email and we will answer you as soon as possible. :)</p>
        The system is now in development version, so there are <b>no charges</b> for the game management.',
    ),
    'briefing' => array
    (
        'title' => 'Specific game info',
        'head-payment' => 'Payment information',
        'head-payment-int' => 'Additional info for international players',
        'head-amount' => 'Amount',
        'head-account' => 'Account',
        'head-reference' => 'Reference',
        'no-map' => 'No specific information present on this game.',
        'not-paid' => 'Your ticket is booked but not paid.',
        'print-ticket' => 'You can print your ticket and you\'re done.',
        'do-print-ticket' => 'Print ticket',
        'person-count' => 'ticket for %d person|ticket for %d persons',
    ),
);