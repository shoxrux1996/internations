<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * App\Models\Group
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Group extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function add(array $data): self
    {
        $group =  self::create([
            'name' => $data['name']
        ]);

        if (Arr::has($data, 'users') and is_array($data['users'])) {
            $group->syncUsers($data['users']);
        }
        return $group;
    }

    public function edit(array $data)
    {
        if (Arr::has($data, 'name') and filled($data['name'])) {
            $this->name = $data['name'];
        }

        $this->save();


        if (Arr::has($data, 'users') and is_array($data['users'])) {
            $this->syncUsers($data['users']);
        }
    }

    public function syncUsers(array $data)
    {
        $this->users()->update(['group_id' => null]);

        if ($data) {
            User::whereIn('id', $data)->update(['group_id' => $this->id]);
        }
    }

    public function scopeFilter($query, array $data)
    {
        if (Arr::has($data, 'name') and filled($data['name'])) {
            $query = $query->where('name', 'LIKE', "%{$data['name']}%");
        }
    }

    public function scopeLoadUsers($query, array $data)
    {
        if (Arr::has($data, 'with_users') and filled($data['with_users'])) {
            $query = $query->with('users');
        }
    }

    public function scopeRetrieve($query, array $data)
    {
        if (Arr::has($data, 'paginate') and filled($data['paginate'])) {
            $query = $query->paginate($data['per_page'] ?? 10);
        } else {
            $query = $query->get();
        }
        return $query;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
