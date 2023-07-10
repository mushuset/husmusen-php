<?php declare(strict_types=1);

class DBInfo
{
    public string $protocolVersion;
    public TransferFormat $protocolVersions;
    public TransferFormat $supportedInputFormats;
    public TransferFormat $supportedOutputFromats;
    public string $instanceName;
    public string $apiUrl;
    public MuseumDetails $museumDetails;
}
?>