<?php

namespace Modules\Loan\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Book\Models\Book;
use Modules\Loan\Models\Loan;
use Modules\User\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Loan\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    protected static ?string $password;
    protected $model = Loan::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'loan_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'return_date' => null,
        ];
    }

    public function returned(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'return_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            ];
        });
    }
}
