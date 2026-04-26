<?php
require __DIR__ . '/bootstrap/app.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    $user = User::create([
        'name' => 'Test User',
        'username' => 'testuser',
        'email' => 'test@email.com',
        'password' => Hash::make('password123'),
    ]);
    
    echo "✓ User created: {$user->email}\n";
    echo "  Email: test@email.com\n";
    echo "  Password: password123\n";
} catch (Exception $e) {
    echo "✗ Error: {$e->getMessage()}\n";
}
