<?php

namespace Modules\Author\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Author\Models\Author;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Author\App\Models\Author>
 */
class AuthorFactory extends Factory
{
    protected static ?string $password;
    protected $model = Author::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
        ];
    }
}
