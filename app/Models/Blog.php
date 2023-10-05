<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Blog extends Model
{
    use HasFactory;
    use Sortable;    

    protected $fillable = ['title','description', 'publication_date', 'user_id'];

    protected $casts = ['user_id' => 'integer'];

    public $sortable = ['publication_date'];

}
