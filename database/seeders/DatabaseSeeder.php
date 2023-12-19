<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Theme;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Theme::create(["title" => "Stripe", "description" => 'Bring simplicity to your shop with this easy scrolling layout.', 'imageUrl' => '/media/templateSnaps/TEMPLATEFAHSIONWER.png',]);
        Theme::create(["title" => "Trellis", "description" => 'Nice and clean and best for shops organized by collection.', 'imageUrl' => '/media/templateSnaps/TEMPLATEFAHSIONWER.png',]);
        Theme::create(["title" => "Chevron", "description" => 'Center your homepage around your shop and its story.', 'imageUrl' => '/media/templateSnaps/TEMPLATEFAHSIONWER.png',]);
    }
}
