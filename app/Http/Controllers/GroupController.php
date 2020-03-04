<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Group::getAllGroup($request)->paginate(config('pagination.admin.per_page'));

        return view('groups/index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = new Group();
        return view('groups/create', compact('group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|min:3|max:50|unique:groups,name',
        ]);

        if ($validator->fails()) {
            return redirect()->route('groups.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Group::storeGroup($request->name);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $notification = [
            'message' => trans('group.group_success_create'),
            'alert-type' => 'success'
        ];

        return redirect()->route('groups.index')->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Group::find($id);

        if (empty($group)) {
            abort(404, trans('common.not_found'));
        }

        return view('groups/edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|min:3|max:50|unique:groups,name,'.$id,
        ]);

        if ($validator->fails()) {
            return redirect()->route('groups.edit', [$id])
                ->withErrors($validator)
                ->withInput();
        }

        $group = Group::find($id);

        if (empty($group)) {
            abort(404, trans('common.not_found'));
        }

        try {
           Group::updateGroup($request->get('name'), $id);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $notification = [
            'message' => trans('group.group_success_update'),
            'alert-type' => 'success'
        ];

        return redirect()->route('groups.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::find($id);

        if (empty($group)) {
            abort(404, trans('common.not_found'));
        }

        try {
            $group->delete();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $notification = [
            'message' => trans('group.group_success_delete'),
            'alert-type' => 'success'
        ];

        return redirect()->route('groups.index')->with($notification);
    }
}
