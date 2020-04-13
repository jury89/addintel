<?php

namespace App\Utilities;

class ElectricOven implements Oven
{

    public function heatUp(): Oven
    {
        echo '10 minutes to heat up';

        return $this;
    }

    public function bake(Pizza $pizza): Oven
    {
        $pizza->setStatus(Pizza::STATUS_COOKED);
        $timeToCook = 5 + count($pizza->getRecipe()->ingredients);
        echo sprintf('%d minutes to bake pizza', $timeToCook);

        return $this;
    }

    public function turnOff(): Oven
    {
        return $this;
    }
}
