<?php
namespace CaioMarcatti12\Repository\Trait;

use Illuminate\Support\Str;

trait UsesUuid
{
    protected static function boot()
    {
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
        parent::boot();
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
