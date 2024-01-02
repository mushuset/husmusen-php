<?php

namespace App\Models;

use Symfony\Component\Yaml\Yaml;
use stdClass;

class HusmusenMuseumDetails
{
    public string $name;
    public string $description;
    public string $address;
    public string $location;
    public string $coordinates;
    public string $website;

    public function __construct(string $name, string $description, string $address, string $location, string $coordinates, string $website)
    {
        $this->name = $name;
        $this->description = $description;
        $this->address = $address;
        $this->location = $location;
        $this->coordinates = $coordinates;
        $this->website = $website;
    }

    static function Default(): HusmusenMuseumDetails
    {
        return new HusmusenMuseumDetails(
            'Museum',
            'Ett helt vanligt museum.',
            'Gatanvägen 4',
            'Kungshamn',
            '0°0′0″ N, 25°0′0″ W',
            'https://example.com'
        );
    }
}

class HusmusenDBInfo
{
    public string $protocolVersion;
    public array $protocolVersions;
    public array $supportedInputFormats;
    public array $supportedOutputFormats;
    public string $instanceName;
    public HusmusenMuseumDetails $museumDetails;

    public function __construct(string $protocolVersion, array $protocolVersions, array $supportedInputFormats, array $supportedOutputFormats, string $instanceName, HusmusenMuseumDetails $museumDetails)
    {
        $this->protocolVersion = $protocolVersion;
        $this->protocolVersions = $protocolVersions;
        $this->supportedInputFormats = $supportedInputFormats;
        $this->supportedOutputFormats = $supportedOutputFormats;
        $this->instanceName = $instanceName;
        $this->museumDetails = $museumDetails;
    }

    static function get_db_info(): stdClass
    {
        return (object) Yaml::parseFile(base_path('data/db_info.yaml'));
    }
}
