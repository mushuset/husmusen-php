<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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

    public static function get_next_item_id(): int
    {
        $id = DB::selectOne('SELECT itemID FROM husmusen_items ORDER BY itemID DESC LIMIT 1');
        if (!$id)
            return 1;
        return $id->itemID + 1;
    }

    public static function search_v1_0_0(string|array $types, string|array $freetext, string|array $keywords, string|array $keyword_mode, string|array $order_by, string|array $reverse): JsonResponse
    {
        // Filter for only valid types.
        $types_as_array = is_array($types) ? $types : preg_split('/,/', $types);  // Make sure `types` isn't an array already, before splitting it into one.
        $valid_types = array_filter($types_as_array, function ($type) {
            return in_array($type, HusmusenItem::$valid_types);
        });
        $types_sql = "('" . join("','", sizeof($valid_types) != 0 ? $valid_types : HusmusenItem::$valid_types) . "')";

        // Make sure `keyword_mode` is not an array.
        $keyword_mode_as_string = is_array($keyword_mode) ? end($keyword_mode) : $keyword_mode;
        // Make sure it is 'AND' or 'OR'.
        $keyword_mode_sane = $keyword_mode_as_string == 'AND' ? 'AND' : 'OR';

        $keywords_as_array = is_array($types) ? $keywords : preg_split('/,/', $keywords);  // Make sure `keyword` isn't an array already, before splitting it into one.
        // TODO: Validate the keywords.
        $valid_keywords = array_filter($keywords_as_array, function ($keyword) {
            return strlen($keyword) > 0;
        });

        // Create keyword SQL; slightly magical. :|
        $keyword_search_sql = $keyword_mode === 'AND'
            // If in "AND-mode", use this magic RegEx created here:
            // This also requires the keywords to be sorted alphabetically.
            ? (sizeof($valid_keywords) > 0 ? "AND keywords RLIKE '(?-i)(?<=,|^)(" . join('(.*,|)', $valid_keywords) . ')(?=,|$)\'' : '')
            // Otherwise, use "OR-mode" with this magic RegEx:
            : (sizeof($valid_keywords) > 0 ? "AND keywords RLIKE '(?-i)(?<=,|^)(" . join('|', $valid_keywords) . ')(?=,|$)\'' : '');

        $VALID_SORT_FIELDS = array('name', 'relevance', 'lastUpdated', 'addedAt', 'itemID');
        // Make sure `order_by` is not an array.
        $order_by_as_string = is_array($order_by) ? end($order_by) : $order_by;
        // Make sure it is 'AND' or 'OR'.
        $order_by_sane = in_array($order_by_as_string, $VALID_SORT_FIELDS) ? $order_by_as_string : 'name';

        /**
         * This formula figures out if the results should be reversed or not.
         * The complexity is needed because the 'relevance' search option works
         * the other way around compared to all other sorting otions.
         */
        $should_reverse_order = $order_by == 'relevance'
            ? ($reverse == '1' || $reverse == 'on' || $reverse == 'true' ? 'ASC' : 'DESC')
            : ($reverse == '1' || $reverse == 'on' || $reverse == 'true' ? 'DESC' : 'ASC');

        // FIXME: Find a way to make sure this is safe and no SQL-incations...
        $sanitized_freetext = $freetext;

        $magic_relevance_sql = "((MATCH(name) AGAINST('$sanitized_freetext' IN BOOLEAN MODE) + 1) * (MATCH(description) AGAINST('$sanitized_freetext' IN BOOLEAN MODE) + 1) - 1) / 3";
        $magic_relevance_search_sql = $freetext != null ? "AND (MATCH(name) AGAINST('$sanitized_freetext' IN BOOLEAN MODE) OR MATCH(description) AGAINST('$sanitized_freetext' IN BOOLEAN MODE))" : '';

        return response()->json(DB::select("
        SELECT *, ($magic_relevance_sql) AS relevance
            FROM husmusen_items
            WHERE type IN $types_sql
            $keyword_search_sql
            $magic_relevance_search_sql
            ORDER BY $order_by_sane $should_reverse_order
        "));
    }
}
