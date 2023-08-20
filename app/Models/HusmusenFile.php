<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * This class represents file metadata stored in the database.
 * @property string $name Human readable name for the file.
 * @property string $fileType MIME type of a file. E.g. 'image/png'
 * @property string $license License and ownership information.
 * @property \Ramsey\Uuid\Uuid $fileID
 * @property \DateTime $addedAt
 * @property \DateTime $updatedAt
 * @property integer $relatedItem The item the file is linked to.
 */
class HusmusenFile extends Model
{
    use HasFactory;

    // Items are stored in the table `husmusen_items`
    protected $table = 'husmusen_files';

    // Specifiy primary key!
    // This is necessary only when the primary key is something other than 'id'.
    protected $primaryKey = 'fileID';

    protected $keyType = 'UUID';

    // Indicates that the primary key is NOT an integer that should be auto-incremented.
    public $incrementing = false;

    // Define the name of the columns that handles creation time.
    const CREATED_AT = 'addedAt';

    // Define the name of the columns that handles modification time.
    const UPDATED_AT = 'updatedAt';
}
