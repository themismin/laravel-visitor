<?php

namespace ThemisMin\LaravelVisitor\Models;


use Illuminate\Database\Eloquent\Model;

class VisitorRegistry extends Model
{

    protected $table = 'visitor_registry';

    protected $fillable = ['clicks'];

    public function hittable()
    {
        return $this->morphTo();
    }
}
