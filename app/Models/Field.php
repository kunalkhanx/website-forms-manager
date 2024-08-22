<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Field extends Model
{
    use HasFactory;

    public function forms():BelongsToMany{
        return $this->belongsToMany(Form::class, FormField::class)->withPivot(['is_unique', 'is_required', 'display']);
    }
}
