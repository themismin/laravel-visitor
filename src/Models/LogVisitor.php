<?php

namespace ThemisMin\LaravelVisitor\Models;


use Illuminate\Database\Eloquent\Model;

class LogVisitor extends Model
{

    protected $table = 'log_visitors';

    public function hittable()
    {
        return $this->morphTo();
    }
}
