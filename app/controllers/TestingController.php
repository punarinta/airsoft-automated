<?php

class TestingController extends BaseController
{
    /**
     * Populate DB with random but logically linked values
     *
     * @throws Exception
     */
    public function populate()
    {
        return $this->execute(function()
        {
            // just a protection
            if (Auth::user()->getId() != 1)
            {
                throw new \Exception('Access denied.');
            }

            // generate teams
            for ($i = 1; $i <= 80; $i++)
            {
                $team = new Team;
                $team->setName('Team ' . $i);
                $team->setOwnerId(mt_rand(10, 500));
                $team->setRegionId(mt_rand(1, 21));
                $team->save();
            }

            // generate users
            for ($i = 1; $i <= 500; $i++)
            {
                $user = new User;
                $user->setNick('User ' . $i);
                $user->setEmail('user-' . $i . '@jesp.ru');
                $user->setTeamId(mt_rand(1, 80));
                $user->setIsEmailValidated(1);
                $user->setPassword('$2y$10$IiMiFJ5iuwGHXraFLPeBR..3QeUUVtPz6dP.Pndo5tLXZQpAZCnXW');
                $user->save();
            }

            return null;
        });
    }
}