<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * App\Models\Admin
 *
 * @property int $id
 * @property string|null $name
 * @property string $username
 * @property string|null $password
 * @property string|null $api_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUsername($value)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Add new admin.
     *
     * @param  array data
     * @return self
     */
    public static function add(array $data): self
    {
        $admin = self::create([
            'name' => $data['name'] ?? null,
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);

        $admin->generateToken();

        return $admin;
    }

    /**
     * Create api_token for logged in user
     * @return string
     */
    public function generateToken(): string
    {
        do {
            $random_string = Str::random(60);
        } while (self::where('api_token', $random_string)->exists());

        $this->api_token = $random_string;
        $this->save();

        return $this->api_token;
    }
}
