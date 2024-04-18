<?php

use Illuminate\Support\Facades\Artisan;

it('generates a password with default options', function () {
    // Act
    Artisan::call('app:generate-password');
    $output = Artisan::output();

    // Assert
    $this->assertMatchesRegularExpression('/Generated Password: .{13}/', $output);
});

it('generates a password with specified length', function () {
    // Act
    Artisan::call('app:generate-password', ['--length' => 16]);
    $output = Artisan::output();

    // Assert
    $this->assertMatchesRegularExpression('/Generated Password: .{16}/', $output);
});

it('generates a password with lowercase letters', function () {
    // Act
    Artisan::call('app:generate-password', ['--lowercase' => true]);
    $output = Artisan::output();

    // Assert
    $this->assertMatchesRegularExpression('/[a-z]/', $output);
});

it('generates a password with uppercase letters', function () {
    // Act
    Artisan::call('app:generate-password', ['--uppercase' => true]);
    $output = Artisan::output();

    // Assert
    $this->assertMatchesRegularExpression('/[A-Z]/', $output);
});

it('generates a password with numbers', function () {
    // Act
    Artisan::call('app:generate-password', ['--numbers' => true]);
    $output = Artisan::output();

    // Assert
    $this->assertMatchesRegularExpression('/[0-9]/', $output);
});

it('generates a password with symbols', function () {
    // Act
    Artisan::call('app:generate-password', ['--symbols' => true]);
    $output = Artisan::output();

    // Assert
    $this->assertMatchesRegularExpression('/[!#$%&()*+@^]/', $output);
});

it('generates a password with mixed options', function () {
    // Act
    Artisan::call('app:generate-password', ['--length' => 16, '--lowercase' => true, '--numbers' => true, '--symbols' => true]);
    $output = Artisan::output();

    // Assert
    $this->assertMatchesRegularExpression('/Generated Password: .{16}/', $output);
    $this->assertMatchesRegularExpression('/[a-z]/', $output);
    $this->assertMatchesRegularExpression('/[0-9]/', $output);
    $this->assertMatchesRegularExpression('/[!#$%&()*+@^]/', $output);
});
