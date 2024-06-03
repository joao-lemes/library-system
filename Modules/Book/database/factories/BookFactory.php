<?php

namespace Modules\Book\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Book\Models\Book;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Book\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected static ?string $password;
    protected $model = Book::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'year_of_publication' => $this->faker->year,
        ];
    }
}
