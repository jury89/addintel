<?php

namespace App\Utilities;

class ElectricOven implements Oven
{

    public function heatUp(): Oven
    {
        return $this;
    }

    public function bake(Pizza $pizza): Oven
    {
        return $this;
    }

    public function turnOff(): Oven
    {
        return $this;
    }
}
