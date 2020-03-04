<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';

    protected $fillable = [
        'name'
    ];

    public function scopeGetAllGroup($query, $request)
    {
        if (!empty($request->status) && is_numeric($request->status)) {
            $query->where('is_active', $request->status);
        }

        return $query->orderBy('created_at', 'ASC');
    }

    public function scopeStoreGroup($query, $name)
    {
        $data_for_group = [
            'name'      => $name,
        ];

        $query->insert($data_for_group);
    }

    public function scopeUpdateGroup($query, $name, $id)
    {
        $data_for_group = [
            'name' => $name
        ];

        $query->where('id', $id)->update($data_for_group);
    }

}
