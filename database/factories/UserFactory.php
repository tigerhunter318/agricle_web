<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'family_name' => $this->faker->name(),
            'nickname' => $this->faker->userName,
            'gender' => $this->faker->randomElement(['man', 'woman']),
            'role' => $this->faker->randomElement(['producer', 'worker']),
            'email' => $this->faker->unique()->safeEmail(),
            'birthday' => $this->faker->date(),
            'address' => $this->faker->address,
            'contact_address' => $this->faker->phoneNumber,
            'cell_phone' => $this->faker->phoneNumber,
            'emergency_phone' => $this->faker->phoneNumber,
            'job' => $this->faker->jobTitle,
            'bio' => Str::random(40),
            'appeal_point' => Str::random(40),
            'management_mode' => $this->faker->randomElement(['individual', 'corporation', 'other']),
            'agency_name' => $this->faker->userAgent,
            'agency_phone' => $this->faker->phoneNumber,
            'insurance' => $this->faker->boolean(25),
            'other_insurance' => $this->faker->jobTitle,
            'product_name' => Str::random(6),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     *
     * @return $this
     */
    public function withPersonalTeam()
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(function (array $attributes, User $user) {
                    return ['name' => $user->name.'\'s Team', 'user_id' => $user->id, 'personal_team' => true];
                }),
            'ownedTeams'
        );
    }
}
