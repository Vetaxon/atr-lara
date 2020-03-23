<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'courses';

    public $timestamps = false;

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
