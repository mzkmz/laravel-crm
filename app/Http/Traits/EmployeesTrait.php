<?php

namespace App\Http\Traits;

trait EmployeesTrait {
    protected $parentColumn = 'reports_to';
    
    public function manager()
    {
        return $this->belongsTo(static::class, $this->parentColumn);
    }

    public function subordinates()
    {
        return $this->hasMany(static::class, $this->parentColumn);
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    public function root()
    {
        return $this->parent
            ? $this->parent->root()
            : $this;
    }
}