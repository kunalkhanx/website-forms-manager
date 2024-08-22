<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    use HasFactory;

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function fields():BelongsToMany{
        return $this->belongsToMany(Field::class, FormField::class)->withPivot(['is_unique', 'is_required', 'display']);;
    }

    public function data():HasMany{
        return $this->hasMany(FormData::class);
    }
}
