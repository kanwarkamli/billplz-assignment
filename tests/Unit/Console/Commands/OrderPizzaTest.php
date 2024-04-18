<?php

it('orders pizza with no customizations', function () {
    $this->artisan('app:order-pizza')
        ->expectsQuestion('Pick a size? (One or more)', ['Small'])
        ->expectsQuestion('[Small] How many pizzas do you want to order?', '1')
        ->expectsQuestion('[Small] Do you want to add any pepperoni or extra cheese?', 'Is it free?')
        ->expectsQuestion('[Small] Obviously it\'s NOT free. So, do you want to add any pepperoni or extra cheese?', 'No, thanks!')
        ->assertExitCode(0);
});

it('orders pizza with customizations apply to all', function () {
    $this->artisan('app:order-pizza')
        ->expectsQuestion('Pick a size? (One or more)', ['Medium'])
        ->expectsQuestion('[Medium] How many pizzas do you want to order?', '2')
        ->expectsQuestion('[Medium] Do you want to add any pepperoni or extra cheese?', 'Yes, please')
        ->expectsQuestion('[Medium] Add pepperoni or extra cheese?', ['Pepperoni +RM5'])
        ->expectsQuestion('[Medium] Apply them on how many pizzas?', 'Apply to all!')
        ->assertExitCode(0);
});

it('orders pizza with customizations apply to some', function () {
    $this->artisan('app:order-pizza')
        ->expectsQuestion('Pick a size? (One or more)', ['Large'])
        ->expectsQuestion('[Large] How many pizzas do you want to order?', '3')
        ->expectsQuestion('[Large] Do you want to add any pepperoni or extra cheese?', 'Yes, please')
        ->expectsQuestion('[Large] Add pepperoni or extra cheese?', ['Cheese +RM6'])
        ->expectsQuestion('[Large] Apply them on how many pizzas?', '1')
        ->assertExitCode(0);
});

it('orders one small pizza with no customizations and checks total price', function () {
    $expectedTotalPrice = 15;

    $this->artisan('app:order-pizza')
        ->expectsQuestion('Pick a size? (One or more)', ['Small'])
        ->expectsQuestion('[Small] How many pizzas do you want to order?', '1')
        ->expectsQuestion('[Small] Do you want to add any pepperoni or extra cheese?', 'Is it free?')
        ->expectsQuestion('[Small] Obviously it\'s NOT free. So, do you want to add any pepperoni or extra cheese?', 'No, thanks!')
        ->expectsOutputToContain("Total price: MYR{$expectedTotalPrice}")
        ->assertExitCode(0);
});

it('orders one small pizza with customizations apply to all and checks total price', function () {
    $expectedTotalPrice = 24;

    $this->artisan('app:order-pizza')
        ->expectsQuestion('Pick a size? (One or more)', ['Small'])
        ->expectsQuestion('[Small] How many pizzas do you want to order?', '1')
        ->expectsQuestion('[Small] Do you want to add any pepperoni or extra cheese?', 'Yes, please')
        ->expectsQuestion('[Small] Add pepperoni or extra cheese?', ['Pepperoni +RM3', 'Cheese +RM6'])
        ->expectsOutputToContain("Total price: MYR{$expectedTotalPrice}")
        ->assertExitCode(0);
});

it('orders two small pizzas with customizations apply to all and checks total price', function () {
    $expectedTotalPrice = 36;

    $this->artisan('app:order-pizza')
        ->expectsQuestion('Pick a size? (One or more)', ['Small'])
        ->expectsQuestion('[Small] How many pizzas do you want to order?', '2')
        ->expectsQuestion('[Small] Do you want to add any pepperoni or extra cheese?', 'Yes, please')
        ->expectsQuestion('[Small] Add pepperoni or extra cheese?', ['Pepperoni +RM3'])
        ->expectsQuestion('[Small] Apply them on how many pizzas?', 'Apply to all!')
        ->expectsOutputToContain("Total price: MYR{$expectedTotalPrice}")
        ->assertExitCode(0);
});

it('orders two small pizzas with customizations apply to some and checks total price', function () {
    $expectedTotalPrice = 33;

    $this->artisan('app:order-pizza')
        ->expectsQuestion('Pick a size? (One or more)', ['Small'])
        ->expectsQuestion('[Small] How many pizzas do you want to order?', '2')
        ->expectsQuestion('[Small] Do you want to add any pepperoni or extra cheese?', 'Yes, please')
        ->expectsQuestion('[Small] Add pepperoni or extra cheese?', ['Pepperoni +RM3'])
        ->expectsQuestion('[Small] Apply them on how many pizzas?', '1')
        ->expectsOutputToContain("Total price: MYR{$expectedTotalPrice}")
        ->assertExitCode(0);
});

it('orders mix pizzas with customizations apply to all and checks total price', function () {
    $expectedTotalPrice = 72;

    $this->artisan('app:order-pizza')
        ->expectsQuestion('Pick a size? (One or more)', ['Small', 'Medium'])
        ->expectsQuestion('[Small] How many pizzas do you want to order?', '1')
        ->expectsQuestion('[Small] Do you want to add any pepperoni or extra cheese?', 'Yes, please')
        ->expectsQuestion('[Small] Add pepperoni or extra cheese?', ['Pepperoni +RM3'])
        ->expectsQuestion('[Medium] How many pizzas do you want to order?', '2')
        ->expectsQuestion('[Medium] Do you want to add any pepperoni or extra cheese?', 'Yes, please')
        ->expectsQuestion('[Medium] Add pepperoni or extra cheese?', ['Pepperoni +RM5'])
        ->expectsQuestion('[Medium] Apply them on how many pizzas?', 'Apply to all!')
        ->expectsOutputToContain("Total price: MYR{$expectedTotalPrice}")
        ->assertExitCode(0);
});

it('orders mix pizzas with customizations apply to some and checks total price', function () {
    // 3 Small pizzas with Pepperoni +RM5 each = (RM15 * 3) + (RM3 * 1) = RM48
    // 2 Large pizzas with Cheese +RM6 each = (RM30 * 2) + (RM6 * 1) = RM66
    // Total = RM48 + RM66 = RM114
    $expectedTotalPrice = 114;

    $this->artisan('app:order-pizza')
        ->expectsQuestion('Pick a size? (One or more)', ['Small', 'Large'])
        ->expectsQuestion('[Small] How many pizzas do you want to order?', '3')
        ->expectsQuestion('[Small] Do you want to add any pepperoni or extra cheese?', 'Yes, please')
        ->expectsQuestion('[Small] Add pepperoni or extra cheese?', ['Pepperoni +RM3'])
        ->expectsQuestion('[Small] Apply them on how many pizzas?', '1')
        ->expectsQuestion('[Large] How many pizzas do you want to order?', '2')
        ->expectsQuestion('[Large] Do you want to add any pepperoni or extra cheese?', 'Yes, please')
        ->expectsQuestion('[Large] Add pepperoni or extra cheese?', ['Cheese +RM6'])
        ->expectsQuestion('[Large] Apply them on how many pizzas?', '1')
        ->expectsOutputToContain("Total price: MYR{$expectedTotalPrice}")
        ->assertExitCode(0);
});

it('orders mix pizzas with mix customizations and checks total price', function () {
    // 2 Small pizzas = RM15 * 2 = RM30
    // 3 Medium pizzas with Pepperoni +RM5 each and Cheese +RM6 each = (RM22 * 3) + ((RM5 + RM6) * 3) = RM99
    // 3 Large pizzas with Pepperoni +RM0 each and Cheese +RM6 each = (RM30 * 3) + (RM6 * 2) = RM102
    // Total = RM30 + RM99 + RM102 = RM231
    $expectedTotalPrice = 231;

    $this->artisan('app:order-pizza')
        ->expectsQuestion('Pick a size? (One or more)', ['Small', 'Medium', 'Large'])
        ->expectsQuestion('[Small] How many pizzas do you want to order?', '2')
        ->expectsQuestion('[Small] Do you want to add any pepperoni or extra cheese?', 'Is it free?')
        ->expectsQuestion('[Small] Obviously it\'s NOT free. So, do you want to add any pepperoni or extra cheese?', 'No, thanks!')
        ->expectsQuestion('[Medium] How many pizzas do you want to order?', '3')
        ->expectsQuestion('[Medium] Do you want to add any pepperoni or extra cheese?', 'Is it free?')
        ->expectsQuestion('[Medium] Obviously it\'s NOT free. So, do you want to add any pepperoni or extra cheese?', 'Sure, add them!')
        ->expectsQuestion('[Medium] Add pepperoni or extra cheese?', ['Pepperoni +RM5', 'Cheese +RM6'])
        ->expectsQuestion('[Medium] Apply them on how many pizzas?', 'Apply to all!')
        ->expectsQuestion('[Large] How many pizzas do you want to order?', '3')
        ->expectsQuestion('[Large] Do you want to add any pepperoni or extra cheese?', 'Yes, please')
        ->expectsQuestion('[Large] Add pepperoni or extra cheese?', ['Pepperoni +RM0', 'Cheese +RM6'])
        ->expectsQuestion('[Large] Apply them on how many pizzas?', '2')
        ->expectsOutputToContain("Total price: MYR{$expectedTotalPrice}")
        ->assertExitCode(0);
});
