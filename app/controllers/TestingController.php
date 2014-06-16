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

            return null;
        });
    }
}