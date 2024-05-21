<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        $user = new \App\Models\User();
        $user->name = 'John Doe';
        $user->email = 'jhondoe@gmail.com';
        $user->password = \Illuminate\Support\Facades\Hash::make('password');
        $user->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
