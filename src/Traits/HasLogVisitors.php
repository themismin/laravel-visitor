<?php

namespace ThemisMin\LaravelVisitor\Traits;


trait HasLogVisitors
{
    /**
     * @return mixed
     */
    public function logVisitors()
    {
        return $this->morphMany(config('visitor.model'), 'hittable');
    }
}
