<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string',
            'with_users' => 'nullable|boolean',
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable|numeric|min:1'
        ]);

        $groups = Group::filter($data)->loadUsers($data)->retrieve($data);

        return GroupResource::collection($groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'users' => 'nullable|array',
            'users.*' => 'required|exists:users,id',
        ]);

        $group = Group::add($data);

        return new GroupResource($group->load('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return new GroupResource($group->load('users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'users' => 'nullable|array',
            'users.*' => 'required|exists:users,id',
        ]);

        $group->edit($data);

        return new GroupResource($group->load('users'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);

        $group->delete();

        return $group->id;
    }
}
