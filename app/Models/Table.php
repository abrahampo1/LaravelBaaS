<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Table extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $with = ['columns'];


    public static function boot()
    {
        parent::boot();
        //on create, assign the user_id
        static::creating(function ($table) {
            $table->user_id = auth()->id() || 1;
        });

        //on delete, drop the table
        static::deleting(function ($table) {
            $table = $table->name;
            DB::statement("DROP TABLE $table");
        });
    }

    //add relation to columns
    public function columns()
    {
        return $this->hasMany(Column::class);
    }
}
