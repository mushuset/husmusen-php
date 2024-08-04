<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;
use stdClass;

class HusmusenDBInfo
{
    static function get_db_info(): stdClass
    {
        return (object) Yaml::parseFile(base_path('data/db_info.yaml'));
    }

    static function update_from_array_data(array $db_info)
    {
        File::put(base_path('data/db_info.yaml'), Yaml::dump($db_info, 2, 4));
        return HusmusenDBInfo::get_db_info();
    }
}
