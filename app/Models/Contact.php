<?php

namespace App\Models;

use App\Traits\FulltextSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Image;

class Contact extends Model
{
    use FulltextSearch;

    protected $table = 'contacts';

    protected $fillable = [
        'first_name', 'avatar', 'group_id', 'country', 'state', 'city', 'phone', 'email', 'group_id', 'note'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function getGravatarAttribute() {
        return URL::to('/') .'/uploads/50/'.$this->attributes['avatar'];
    }

    public function scopeStoreContact($query, $request, $path, $dimensions)
    {
        $file = $request->file('avatar');
        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        Image::make($file)->save($path . '/' . $fileName);

        foreach ($dimensions as $row) {
            $canvas = Image::canvas($row, $row);

            $resizeImage  = Image::make($file)->resize($row, $row, function($constraint) {
                $constraint->aspectRatio();
            });

            if (!File::isDirectory($path . '/' . $row)) {
                File::makeDirectory($path . '/' . $row);
            }

            $canvas->insert($resizeImage, 'center');
            $canvas->save($path . '/' . $row . '/' . $fileName);
        }

        $data_for_contact = [
            'avatar'     => $fileName,
            'last_name'  => $request->last_name,
            'address'    => $request->address,
            'zip'        => $request->zip,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'group_id'   => $request->group,
            'first_name' => $request->first_name,
            'country'    => $request->country,
            'state'      => $request->state,
            'city'       => $request->city,
            'note'       => $request->note,
        ];

        $query->insert($data_for_contact);
    }

    public function scopeUpdateContact($query, $request, $id)
    {
        $data_for_contact = [
            'last_name'  => $request->last_name,
            'address'    => $request->address,
            'zip'        => $request->zip,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'group_id'   => $request->group,
            'first_name' => $request->first_name,
            'country'    => $request->country,
            'state'      => $request->state,
            'city'       => $request->city,
            'note'       => $request->note,
        ];

        $query->where('id', $id)->update($data_for_contact);
    }

    public function scopeGetAllContact($query, $request)
    {
        if (!empty($request->name)) {
            $columns = 'name';

            $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($request->name));
        }

        return $query->orderBy('created_at', 'DESC');
    }
}
