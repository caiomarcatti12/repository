<?php
namespace CaioMarcatti12\Repository\Trait;

use CaioMarcatti12\Data\UUID;

trait UsesUuid
{
    protected static function boot()
    {
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = UUID::v4();
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
