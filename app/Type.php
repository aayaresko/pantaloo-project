<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public function slots()
    {
        return $this->hasMany('App\Type');
    }

    static function getSelect($val = false)
    {
        $html = '<select name="type_id" class="form-control">';

        foreach (Type::all() as $type)
        {
            if($type->id == $val) $html = $html . '<option value="' . $type->id . '" selected>' . $type->name . '</option>';
            else $html = $html . '<option value="' . $type->id . '">' . $type->name . '</option>';
        }

        $html = $html . '</select>';

        return $html;
    }
}
