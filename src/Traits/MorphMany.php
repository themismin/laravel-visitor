<?php


namespace ThemisMin\LaravelVisitor\Traits;


trait MorphMany
{
    /**
     * @return mixed
     */
    public function hittable()
    {
        return $this->morphMany(config('visitor.model'), 'hittable');
    }
}
