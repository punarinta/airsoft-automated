<?php

class TestingController extends BaseController
{
    /**
     * Populate DB with random but logically linked values
     *
     * GET /testing/populate
     *
     * @throws Exception
     */
    public function populate()
    {
        return $this->execute(function()
        {
            // just a protection
            if (!Config::get('app.debug'))
            {
                throw new \Exception('Access denied.');
            }

            $maxRegions = 21;   // depends on current amount in DB
            $maxUsers = 500;
            $maxTeams = 80;
            $maxGames = 20;
            $maxGameParties = 50;
            $maxTicketsPerTemplate = 100;

            // generate teams
            for ($i = 1; $i <= $maxTeams; $i++)
            {
                $team = new Team;
                $team->setName('Team ' . $i);
                $team->setOwnerId(mt_rand(10, $maxUsers));
                $team->setRegionId(mt_rand(1, $maxRegions));
                $team->save();
            }

            // generate users
            for ($i = 1; $i <= $maxUsers; $i++)
            {
                $user = new User;
                $user->setNick('User ' . $i);
                $user->setEmail('user-' . $i . '@jesp.ru');
                $user->setTeamId(mt_rand(1, $maxTeams));
                $user->setIsValidated(1);
                $user->setIsEmailValidated(1);
                $user->setPassword('$2y$10$IiMiFJ5iuwGHXraFLPeBR..3QeUUVtPz6dP.Pndo5tLXZQpAZCnXW');
                $user->save();
            }

            // generate games
            for ($i = 1; $i <= $maxGames; $i++)
            {
                $game = new Game;
                $game->setName('Game ' . $i);
                $game->setOwnerId(1/*mt_rand(10, $maxUsers)*/);
                $game->setRegionId(mt_rand(1, $maxRegions));
                $game->setStartsAt(date('Y-m-d H:i:s', time() + 86400 * mt_rand(21, 30)));
                $game->setEndsAt(date('Y-m-d H:i:s', time() + 86400 * mt_rand(31, 40)));
                $game->save();
            }

            // generate game parties
            for ($i = 1; $i <= $maxGameParties; $i++)
            {
                $gameId = mt_rand(3, $maxGames);
                $price = 100 * mt_rand(100, 600);

                $bruttoIncome = $price;
                $ppIncome = 0;
                $myIncome = $bruttoIncome * 3 / 100 + 300;
                $nettoIncome = $bruttoIncome - $ppIncome - $myIncome * 1.25;
                $vatPaid = $myIncome * 0.25;

                $gameParty = new GameParty;
                $gameParty->setName('Game Party ' . $i);
                $gameParty->setGameId($gameId);
                $gameParty->setPlayersLimit(mt_rand(100, 500));
                $gameParty->save();

                // include ticket templates
                $ticketTemplate = new TicketTemplate;
                $ticketTemplate->setGameId($gameId);
                $ticketTemplate->setGamePartyId($gameParty->getId());
                $ticketTemplate->setIsCash(mt_rand(0,100)>50 ? 1 : 0);
                $ticketTemplate->setPrice($price);
                $ticketTemplate->setPriceDateStart(date('Y-m-d H:i:s', time() + 86400 * mt_rand(-20, 0)));
                $ticketTemplate->setPriceDateEnd(date('Y-m-d H:i:s', time() + 86400 * mt_rand(0, 20)));
                $ticketTemplate->save();

                $maxTickets = mt_rand($maxTicketsPerTemplate/2, $maxTicketsPerTemplate);

                // generate tickets
                for ($j = 1; $j <= $maxTickets; $j++)
                {
                    $ticket = new Ticket;
                    $ticket->setTicketTemplateId($ticketTemplate->getId());
                    $ticket->setGamePartyId($gameParty->getId());
                    $ticket->setUserId(mt_rand(1, $maxUsers));
                    $ticket->setStatus(mt_rand(0,1) ? Ticket::STATUS_BOOKED : Ticket::STATUS_BOOKED | Ticket::STATUS_PAID);
                    $ticket->setBrutto($bruttoIncome);
                    $ticket->setNetto($nettoIncome);
                    $ticket->setVat($vatPaid);
                    $ticket->save();
                }
            }

            return null;
        });
    }
}