<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HusmusenItem extends Model
{
    use HasFactory;

    // Items are stored in the table `husmusen_items`
    protected $table = 'husmusen_items';

    // Specifiy primary key!
    // This is necessary only when the primary key is something other than 'id'.
    protected $primaryKey = 'itemID';

    // Don't let Laravel manage creation and modification timestamps.
    // (Husmusen does this itself.)
    public $timestamps = false;
}
