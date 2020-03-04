<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public $path;
    public $dimensions;

    public function __construct()
    {
        $this->path = public_path() . '/uploads';
        $this->dimensions = ['50'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $contacts = Contact::getAllContact($request)->paginate(config('pagination.admin.per_page'));

        return view('contacts/index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contact = new Contact();

        $countries = DB::table("countries")->pluck("name","id");
        $groups = DB::table("groups")->pluck("name","id");

        return view('contacts/create', compact('contact', 'countries', 'groups'));
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
            'group'      => 'required',
            'first_name' => 'required|min:2|max:50',
            'country'    => 'required|integer',
            'state'      => 'required|integer',
            'city'       => 'required|integer',
            'avatar'     => 'required|image|mimes:jpg,png,jpeg',
            'last_name'  => 'required|min:2|max:50',
            'address'    => 'required|min:2|max:100',
            'zip'        => 'required|digits:5',
            'email'      => 'required|min:2|max:50|unique:contacts,email',
            'phone'      => 'required|digits_between:7,15',
            'note'       => 'min:2|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->route('contacts.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            if (!File::isDirectory($this->path)) {
                File::makeDirectory($this->path);
            }

            Contact::storeContact($request, $this->path, $this->dimensions);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $notification = [
            'message' => trans('contact.contact_success_create'),
            'alert-type' => 'success'
        ];

        return redirect()->route('contacts.index')->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Contact::find($id);

        if (empty($contact)) {
            abort(404, trans('common.not_found'));
        }

        $countries = DB::table("countries")->pluck("name", "id");
        $states    = DB::table("states")->where('country_id', $contact->country)->pluck("name", "id");
        $cities    = DB::table("cities")->where('state_id', $contact->state)->pluck("name", "id");

        $groups    = DB::table("groups")->pluck("name", "id");

        return view('contacts/edit', compact('contact', 'countries', 'states', 'cities', 'groups'));
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
            'group'     => 'required',
            'first_name' => 'required|min:2|max:50',
            'country'    => 'required|integer',
            'state'      => 'required|integer',
            'city'       => 'required|integer',
            'last_name'  => 'required|min:2|max:50',
            'address'    => 'required|min:2|max:100',
            'zip'        => 'required|digits:5',
            'email'      => 'required|min:2|max:50|unique:contacts,email,'.$id,
            'phone'      => 'required|digits_between:7,15',
            'note'       => 'min:2|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->route('contacts.edit', [$id])
                ->withErrors($validator)
                ->withInput();
        }

        $contact = Contact::find($id);

        if (empty($contact)) {
            abort(404, trans('common.not_found'));
        }

        try {
            Contact::updateContact($request, $id);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $notification = [
            'message' => trans('contact.contact_success_update'),
            'alert-type' => 'success'
        ];

        return redirect()->route('contacts.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (empty($contact)) {
            abort(404, trans('common.not_found'));
        }

        try {
            $contact->delete();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $notification = [
            'message' => trans('contact.contact_success_delete'),
            'alert-type' => 'success'
        ];

        return redirect()->route('contacts.index')->with($notification);
    }

    public function getStateCollection(Request $request)
    {
        $states = DB::table("states")
            ->where("country_id",$request->country_id)
            ->pluck("name","id");
        return response()->json($states);
    }

    public function getCityCollection(Request $request)
    {
        $cities = DB::table("cities")
            ->where("state_id",$request->state_id)
            ->pluck("name","id");
        return response()->json($cities);
    }
}
