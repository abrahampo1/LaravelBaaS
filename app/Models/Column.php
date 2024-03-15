<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'table_id', 'reference_table', 'reference_column'];

    //add relation to table
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
