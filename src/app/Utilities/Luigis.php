<?php

namespace App\Utilities;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderRecipe;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Support\Collection;

class Luigis
{
    /** @var Fridge */
    private $fridge;
    /** @var Oven */
    private $oven;

    public function __construct(Oven $oven = null)
    {
        $this->fridge = new Fridge();
        $this->oven = $oven ? $oven : new ElectricOven();
    }

    public function restockFridge(): void
    {
        /** @var Ingredient $ingredient */
        foreach (Ingredient::all() as $ingredient) {
            $this->fridge->add($ingredient, 10);
        }
    }

    // todo create this function (returns a collection of cooked pizzas)

    /**
     * @param Order $order
     * @return Pizza[]|Collection
     */
    public function deliver(Order $order): Collection
    {
        $pizzas = new Collection();

        /** @var OrderRecipe $orderRecipe */
        foreach ($order->orderRecipes()->get() as $orderRecipe) {
            $pizza = $this->prepare(Recipe::find($orderRecipe->recipe_id));
            $this->cook($pizza);

            $pizzas[] = $pizza;
        }

        return $pizzas;
    }

    private function prepare(Recipe $recipe): Pizza
    {
        $fridge = new Fridge();
        /** @var Ingredient $ingredient */
        foreach ($recipe->ingredients as $ingredient) {
            /** @var RecipeIngredient $recipeIngredient */
            $recipeIngredient = RecipeIngredient::where('recipe_id', '=', $recipe->id)
                ->where('ingredient_id', '=', $ingredient->id)
                ->first();

            $amount = $recipeIngredient->amount;

            if (false === $fridge->has($ingredient, $amount)) {
                $fridge->add($ingredient, ($amount - $fridge->amount($ingredient)));
            }

            $fridge->take($ingredient, $amount);
        }

        return new Pizza($recipe);
    }

    private function cook(Pizza $pizza): void
    {
        $oven = new ElectricOven();
        $oven->heatUp();
        $oven->bake($pizza);
    }
}
