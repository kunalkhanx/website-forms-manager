<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormData extends Model
{
    use HasFactory;

    // protected function casts(): array
    // {
    //     return [
    //         'data' => 'json',
    //     ];
    // }

    public function form():BelongsTo{
        return $this->belongsTo(Form::class);
    }
}
