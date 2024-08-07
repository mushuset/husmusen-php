<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

/**
 * Details of the actual (physical) museum.
 *
 * @property string $name
 * @property string $description A short description of what the museum is about.
 * @property string $address     A full street address of the museum.
 * @property string $location    A more general location of the museum, e.g. town/village/city.
 * @property string $coordinates Coordinates to the museum.
 * @property string $website     URL to the museum's website.
 */
class MuseumDetails
{
    // THIS IS A DUMMY CLASS; IT CAN'T BE CREATED ON ITS OWN.
    // It's only meant for documentation purposes.
}

/**
 * A description of the database and the museum it belongs to.
 *
 * @property string   $protocolVersion        The latest supported protocol version.
 * @property string[] $protocolVersions       A list of all supported protocols.
 * @property string[] $supportedInputFormats  A list of all supported input formats (mime types).
 * @property string[] $supportedOutputFormats A list of all supported input formats (mime types).
 * @property string   $instanceName           The name of the Husmusen instance.
 * @property string   $museumDetails          The details of the actual museums.
 */
class HusmusenDBInfo
{
    /**
     * Gets the DBInfo. It is stored in `data/db_info.yaml`.
     *
     * @return \stdClass the DBInfo
     */
    public static function get_db_info(): \stdClass
    {
        return (object) Yaml::parseFile(base_path('data/db_info.yaml'));
    }

    /**
     * Sets (**overwrites!**) the DBInfo. It is stored in `data/db_info.yaml`.
     *
     * @return \stdClass the new DBInfo
     */
    public static function update_from_array_data(array $db_info)
    {
        File::put(base_path('data/db_info.yaml'), Yaml::dump($db_info, 2, 4));

        return HusmusenDBInfo::get_db_info();
    }
}
