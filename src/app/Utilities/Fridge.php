<?php


namespace App\Utilities;


use App\Models\FridgeContent;
use App\Models\Ingredient;
use InvalidArgumentException;

class Fridge
{
    private $stock = [];

    public function __construct()
    {
        /** @var FridgeContent $fridgeContent */
        foreach (FridgeContent::with('ingredient')->get() as $fridgeContent) {
            $this->add($fridgeContent->ingredient, $fridgeContent->amount);
        }
    }

    public function add(Ingredient $ingredient, int $amount): self
    {
        $name = $ingredient->name;
        if (!isset($this->stock[$name])) {
            $this->stock[$name] = 0;
        }

        $this->updateStock($ingredient, $amount);

        return $this;
    }

    public function take(Ingredient $ingredient, int $amount): self
    {
        $existingAmount = $this->amount($ingredient);
        if ($existingAmount < $amount) {
            throw new InvalidArgumentException("Fridge has {$existingAmount} of {$ingredient->name}, you asked for {$amount}");
        }

        $this->updateStock($ingredient, $amount * -1);

        return $this;
    }

    private function updateStock(Ingredient $ingredient, int $amount): void
    {
        $name = $ingredient->name;
        $this->stock[$name] += $amount;

        $ingredient->stock()->updateOrCreate(
            ['ingredient_id' => $ingredient->id],
            ['amount' => $this->stock[$name]]
        );
        $ingredient->refresh();
    }

    // Check this fridge stock, not ingredient relation. See updateStock on how the stock works
    public function amount(Ingredient $ingredient): int
    {
        return $this->stock[$ingredient->name] ?? 0;
    }

    public function has(Ingredient $ingredient, int $amount): bool
    {
        $name = $ingredient->name;

        return isset($this->stock[$name]) && $this->stock >= $amount;
    }
}
