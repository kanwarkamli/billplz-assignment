<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

Artisan::command('app:generate-password {--length=} {--lowercase} {--uppercase} {--numbers} {--symbols}', function () {
    $lowercaseLetters = 'abcdefghijklmnopqrstuvwxyz';
    $uppercaseLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $symbols = ['!', '#', '$', '%', '&', '(', ')', '*', '+', '@', '^'];

    $length = $this->option('length') ?: 13;

    $password = '';

    if ($this->option('lowercase')
        || (!$this->option('lowercase') && !$this->option('uppercase') && !$this->option('numbers') && !$this->option('symbols'))
    ) {
        $password .= $lowercaseLetters;
    }

    if ($this->option('uppercase')) {
        $password .= $uppercaseLetters;
    }

    if ($this->option('numbers')) {
        $password .= $numbers;
    }

    if ($this->option('symbols')) {
        $password .= implode('', $symbols);
    }

    $password = Str::limit(str_shuffle($password), $length);

    $this->info('Generated Password: ' . $password);
})
    ->describe('Unleash the password wizard!')
    ->purpose('This command is your personal password wizard, conjuring up passwords with a sprinkle of lowercase, a dash of uppercase, a pinch of numbers, and a swirl of symbols.');


Artisan::command('app:order-pizza', function () {
    $pizzas = [];
    $totalPrice = 0;

    $customItemsPrice['Pepperoni']['Small'] = 3;
    $customItemsPrice['Pepperoni']['Medium'] = 5;
    $customItemsPrice['Pepperoni']['Large'] = 0;

    $customItemsPrice['Cheese']['Small'] = 6;
    $customItemsPrice['Cheese']['Medium'] = 6;
    $customItemsPrice['Cheese']['Large'] = 6;

    $prices = [
        'Small' => 15,
        'Medium' => 22,
        'Large' => 30,
    ];

    $calculatePrice = function($size, $quantity) use ($prices) {
        return $prices[$size] * $quantity ?? 0;
    };

    $calculateCustomItemPrice = function($size, $pizzaItem, $quantity) use ($customItemsPrice) {
        $pizzaItem = explode(' ', $pizzaItem);
        return isset($customItemsPrice[$pizzaItem[0]][$size])
            ? $customItemsPrice[$pizzaItem[0]][$size] * $quantity
            : 0;
    };

    info('Welcome to Laravel Oven: Baking Pizzas & Code to Perfection!');

    table(
        ['Size', 'Price (RM)'],
        [
            ['Small', '15'],
            ['Medium', '22'],
            ['Large', '30'],
        ]
    );

    info('Are you ready to order? Let\'s go!');

    $pizzaSize = multiselect(
        label: 'Pick a size? (One or more)',
        options: ['Small', 'Medium', 'Large'],
        required: true,
        hint: 'Select the size of the pizza you want to order.',
    );

    foreach ($pizzaSize as $size) {
        $quantity = select(
            label: "[{$size}] How many pizzas do you want to order?",
            options: ['1', '2', '3', '4', 'I need more!'],
            default: '1',
            required: true,
        );

        if ($quantity === 'I need more!') {
            $quantity = text(
                label: "[{$size}] How many pizza exactly do you need?",
                validate: ['quantity' => 'required|numeric'],
            );
        }

        // Topping selection
        $customPizza = select(
            label: "[{$size}] Do you want to add any pepperoni or extra cheese?",
            options: ['Yes, please', 'Is it free?'],
            default: 'Yes, please',
            required: true,
        );

        if ($customPizza === 'Is it free?') {
            $customPizza = select(
                label: "[{$size}] Obviously it's NOT free. So, do you want to add any pepperoni or extra cheese?",
                options: ['Sure, add them!', 'No, thanks!'],
                default: 'Sure, add them!',
                required: true,
            );
        }

        // Customer wants to customise the pizza
        if ($customPizza === 'Sure, add them!' || $customPizza === 'Yes, please') {
            $customTotalPrice = 0;
            $customQuantity = 'Apply to all!';

            $customItems = multiselect(
                label: "[{$size}] Add pepperoni or extra cheese?",
                options: ['Pepperoni +RM' . $customItemsPrice['Pepperoni'][$size], 'Cheese +RM' . $customItemsPrice['Cheese'][$size]],
                required: true,
            );

            if ($customItems) {
                if ($quantity > 1) {
                    $options = range(1, $quantity);
                    array_unshift($options, "Apply to all!");

                    $customQuantity = select(
                        label: "[{$size}] Apply them on how many pizzas?",
                        options: $options,
                        default: '1',
                        required: true,
                    );
                }

                foreach ($customItems as $item) {
                    $customTotalPrice += $calculateCustomItemPrice($size, $item, $customQuantity === 'Apply to all!' ? $quantity : $customQuantity);
                }
            }

            $pizzas[] = compact('size', 'quantity', 'customItems', 'customQuantity', 'customTotalPrice');
        } else {
            $pizzas[] = compact('size', 'quantity');
        }

        $totalPrice += $calculatePrice($size, $quantity);
    }

    $pizzaTableData = [];
    foreach ($pizzas as $pizza) {
        $totalPrice += $pizza['customTotalPrice'] ?? 0;
        $addOnQuantity = '';

        if (isset($pizza['customQuantity'])) {
            $addOnQuantity .= $pizza['customQuantity'] === 'Apply to all!'
                ? ' x ' . $pizza['quantity']
                : ' x ' . $pizza['customQuantity'];
        }

        $pizzaTableData[] = [
            'Size' => $pizza['size'],
            'Quantity' => $pizza['quantity'],
            'Unit Price (RM)' => $prices[$pizza['size']],
            'Add-on (RM)' => implode(', ', $pizza['customItems'] ?? []) . $addOnQuantity,
            'Total Price (RM)' => $calculatePrice($pizza['size'], $pizza['quantity']) + ($pizza['customTotalPrice'] ?? 0),
        ];
    }

    info('Your final order as follows:');

    table(
        ['Size', 'Quantity', 'Unit Price (RM)', 'Add-on', 'Amount (RM)'],
        $pizzaTableData,
    );

    info("Total price: MYR{$totalPrice}");
})
    ->describe('Your personal pizza calculator!')
    ->purpose('This command is your pizza sidekick, calculating the cost of your pizza feast based on size, quantity, and those delicious extra toppings.');
;

Artisan::command('app:snail-progress', function () {
    // Depth of the well
    $well_depth = 11;

    // Snail's climbing progress during the day
    $climb_per_day = 1;

    // Calculate the number of days needed to climb to the top of the well
    $days_to_top = ceil($well_depth / $climb_per_day);

    // Add one extra day to get completely out of the well
    $total_days = $days_to_top + 1;

    // Create a new progress bar (with a total step count of $total_days)
    $progressBar = $this->output->createProgressBar($total_days);

    // Simulate snail's progress for animation
    for ($day = 1; $day <= $total_days; $day++) {
        if ($day <= $days_to_top) {
            // Snail is still climbing
            $progress = $day * $climb_per_day;
            $this->line("Day $day: Snail has climbed $progress meters.");
        } else {
            // Snail has climbed out of the well
            $this->line("Day $day: Snail has climbed out of the well!");
        }

        // Advance the progress bar by one step
        $progressBar->advance();

        sleep(1); // Add a delay for animation effect (1 second delay)
    }

    // Ensure that the progress bar is at 100%
    $progressBar->finish();

})->describe('Simulate snail\'s progress as it climbs out of a well');
