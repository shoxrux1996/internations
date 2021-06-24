<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Group $group
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|User filter(array $data)
 */
class User extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function add(array $data)
    {
        return self::create([
            'name' => $data['name'],
            'group_id' => $data['group_id'] ?? null
        ]);
    }

    public function edit(array $data)
    {
        if (Arr::has($data, 'name') and filled($data['name'])) {
            $this->name = $data['name'];
        }

        if (Arr::has($data, 'group_id')) {
            $this->group_id = $data['group_id'];
        }

        $this->save();
    }

    public function scopeFilter($query, array $data)
    {
        if (Arr::has($data, 'name') and filled($data['name'])) {
            $query = $query->where('name', 'LIKE', "%{$data['name']}%");
        }

        if (Arr::has($data, 'group_id')) {
            $query = $query->where('group_id', $data['group_id']);
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

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
