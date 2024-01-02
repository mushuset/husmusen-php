<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * This class represents a log entry stored in the database.
 * @property string $prefix A "channel" for the log, e.g. "general" or "database"
 * @property string $timestamp When that log entry was created.
 * @property string $message The actual log message.
 */
class HusmusenLog extends Model
{
    // Items are stored in the table `husmusen_items`
    protected $table = 'husmusen_logs';

    // Specifiy primary key!
    // This is necessary only when the primary key is something other than 'id'.
    protected $primaryKey = null;

    protected $keyType = 'string';

    static function write(string $prefix, string $message)
    {
        DB::table('husmusen_logs')->insert(['prefix' => $prefix, 'message' => $message]);
    }
}
