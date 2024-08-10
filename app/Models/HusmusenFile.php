<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * This class represents file metadata stored in the database.
 *
 * @property string            $name        Human readable name for the file.
 * @property string            $description More detailed information about the file.
 * @property string            $type        MIME type of a file. E.g. 'image/png'
 * @property string            $license     License and ownership information.
 * @property \Ramsey\Uuid\Uuid $fileID
 * @property \DateTime         $addedAt
 * @property \DateTime         $updatedAt
 * @property int               $relatedItem The item the file is linked to.
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
    public const CREATED_AT = 'addedAt';

    // Define the name of the columns that handles modification time.
    public const UPDATED_AT = 'updatedAt';

    /**
     * Creates a `HusmusenFile` object from an array with the same properties as the class.
     */
    public static function from_array_data(array $fromData): HusmusenFile
    {
        $file = new HusmusenFile();
        $file->name = $fromData['name'];
        $file->type = $fromData['type'];
        $file->description = $fromData['description'];
        $file->license = $fromData['license'];
        $file->fileID = (string) Str::orderedUuid();
        $file->relatedItem = $fromData['relatedItem'] ?? null;

        // Not setting files, since that's a relation.
        return $file;
    }

    /**
     * Updates a `HusmusenFile` in the database from the given array data.
     *
     * The array needs to provide the following properties:
     * - `name` (`string`)
     * - `description` (`string`)
     * - `license` (`string`)
     * - `relatedItem` (`int`)
     *
     * See the documentation for `HusmusenFile` for more information on what the properties should be.
     */
    public static function update_from_array_data(HusmusenFile $file, array $fromData): bool
    {
        $file->name = $fromData['name'];
        $file->description = $fromData['description'];
        $file->license = $fromData['license'];
        $file->relatedItem = $fromData['relatedItem'];

        // Not setting files, since that's a relation.
        return $file->save();
    }
}
