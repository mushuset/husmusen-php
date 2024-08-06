<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

/**
 * A keyword used to search the database.
 *
 * **Do note that this is not a model that is stored in the database!**
 *
 * @property HusmusenItemType $type The `HusmusenItemType` the keyword is for.
 * @property string $word The word itself.
 * @property string $description A description of when to use the keyword. */
class HusmusenKeyword
{
    public static function from_array_data(array $fromData): HusmusenKeyword
    {
        $keyword = new HusmusenKeyword();
        try {
            $keyword->type = $fromData['type'];
            $keyword->word = $fromData['word'];
            $keyword->description = $fromData['description'];

            // Not setting files, since that's a relation.
            return $keyword;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Gets all keywords.
     *
     * @return HusmusenKeyword[]
     */
    public static function get_all(): array
    {
        return array_map(fn ($keyword_object): HusmusenKeyword => HusmusenKeyword::from_array_data($keyword_object), Yaml::parseFile(base_path('data/keywords.yaml')));
    }

    /**
     * Gets all keywords of specified item types.
     *
     * @param HusmusenItemType[] $types
     *
     * @return HusmusenKeyword[]
     */
    public static function get_all_of_types(array $types): array
    {
        $keywords = HusmusenKeyword::get_all();

        return array_filter($keywords, fn ($type) => in_array($type, $types));
    }

    /**
     * Updates the keywords list.
     *
     * Do note that this **OVERWERITES** the previous keywords list.
     *
     * @param HusmusenKeyword[] $keywords the new keywords
     *
     * @return HusmusenKeyword[] the new keywords list
     */
    public static function update_keywords(array $keywords): array
    {
        // Sort the keywords in alphabetical order.
        // Makes them easier to edit in conjuction with the UI and HusmusenKeyword::get_all_as_edit_friendly_format()
        sort($keywords, SORT_STRING);
        File::put(base_path('data/keywords.yaml'), Yaml::dump($keywords, 2, 4));

        return HusmusenKeyword::get_all();
    }

    /**
     * Gets the keywords in the following format:
     *
     *    ITEMTYPE: KEYWORD: DESCRIPTION
     */
    public static function get_all_as_edit_friendly_format(): string
    {
        $keywords = HusmusenKeyword::get_all();
        $keyword_string_array = array_map(fn ($keyword): string => $keyword->type.': '.$keyword->word.': '.$keyword->description, $keywords);

        natcasesort($keyword_string_array);

        return implode("\n", $keyword_string_array);
    }
}
