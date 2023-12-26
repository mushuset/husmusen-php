<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents all possible item types.
 */
enum HusmusenItemType
{
    case ArtPiece;
    case Blueprint;
    case Book;
    case Building;
    case Collection;
    case Concept;
    case CulturalEnvironment;
    case CulturalHeritage;
    case Document;
    case Exhibition;
    case Film;
    case Group;
    case HistoricalEvent;
    case InteractiveResource;
    case Map;
    case Organisation;
    case Person;
    case Photo;
    case PhysicalItem;
    case Sketch;
    case Sound;
}

/**
 * This class represents an item stored in the database.
 * @property string $name A human readable name for the item.
 * @property string $description A longer description of what the item is.
 * @property string $keywords A comma separeted list of search keywords for the item.
 * @property HusmusenItemType $type
 * @property int $itemID A unique ID for the item.
 * @property \DateTime $addedAt
 * @property \DateTime $updatedAt
 * @property mixed $customData Custom data for the item
 * @property mixed $itemData Custom data for the item
 * @property boolean $isExpired Whether the item has expired.
 * @property string $expireReason The reason the item has expired, if applicable.
 * @property array<int, HusmusenFile> $files Files associated with the item. (Files are fetched via a join operation.)
 */
class HusmusenItem extends Model
{
    use HasFactory;

    // Items are stored in the table `husmusen_items`
    protected $table = 'husmusen_items';

    // Specifiy primary key!
    // This is necessary only when the primary key is something other than 'id'.
    protected $primaryKey = 'itemID';

    // Indicates that the primary key is NOT an integer that should be auto-incremented.
    public $incrementing = false;

    // Define the name of the columns that handles creation time.
    const CREATED_AT = 'addedAt';

    // Define the name of the columns that handles modification time.
    const UPDATED_AT = 'updatedAt';

    public static $valid_types = array(
        'ArtPiece',
        'Blueprint',
        'Book',
        'Building',
        'Collection',
        'Concept',
        'CulturalEnvironment',
        'CulturalHeritage',
        'Document',
        'Exhibition',
        'Film',
        'Group',
        'HistoricalEvent',
        'InteractiveResource',
        'PhysicalItem',
        'Map',
        'Organisation',
        'Person',
        'Photo',
        'Sketch',
        'Sound'
    );
}
