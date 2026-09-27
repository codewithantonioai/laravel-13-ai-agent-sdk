<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $antonio = User::where('email', 'antonio@example.com')->first();
        $tags = Tag::all();

        Note::factory()
            ->count(45)
            ->for($antonio)
            ->create()
            ->each(fn (Note $note) => $note->tags()->attach($tags->random(2)));
    }
}
