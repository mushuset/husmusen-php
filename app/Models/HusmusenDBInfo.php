<?php

namespace App\Models;

use Symfony\Component\Yaml\Yaml;
use stdClass;

class HusmusenDBInfo
{
    static function get_db_info(): stdClass
    {
        return (object) Yaml::parseFile(base_path('data/db_info.yaml'));
    }
}
