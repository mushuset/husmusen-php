<?php

use App\Models\HusmusenDBInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

const DOTENV_LOCATION = '../.env';
const DOTENV_READ_ERROR = 'Problem under inläsning av `.env`! (Dubbelkolla att `.env.example` är kopierad till `.env`.)';
const DOTENV_WRITE_ERROR = 'Problem under skrivning av `.env` file! (Dubbelkolla att filrättigheter (file permissions) är korrekt inställda på din server.)';
const ALL_GOOD_MESSAGE = 'Allt ska ha gått bra!';

if (env('APP_DEBUG', false)) {
    Route::get('/setup', function () { return view('setup'); });

    Route::post('/setup/instance-info', function (Request $request) {
        $app_name = $request->get('APP_NAME', 'Husmusen');
        $app_url = $request->get('APP_URL', 'http://localhost');

        if (!$dot_env_file_content = file_get_contents(DOTENV_LOCATION)) {
            return Response::make(DOTENV_READ_ERROR, 500);
        }

        $dot_env_file_content = preg_replace('/^APP_NAME=.*$/m', 'APP_NAME='.$app_name, $dot_env_file_content);
        $dot_env_file_content = preg_replace('/^APP_URL=.*$/m', 'APP_URL='.$app_url, $dot_env_file_content);

        if (!$dot_env_file_content = file_put_contents(DOTENV_LOCATION, $dot_env_file_content)) {
            return Response::make(DOTENV_WRITE_ERROR, 500);
        }

        return ALL_GOOD_MESSAGE;
    });

    Route::post('/setup/museum-info', function (Request $request) {
        HusmusenDBInfo::update_from_array_data($request->all());

        return ALL_GOOD_MESSAGE;
    })->middleware('yaml_parser');

    Route::post('/setup/db-info', function (Request $request) {
        $db_host = $request->get('DB_HOST');
        $db_port = $request->get('DB_PORT');
        $db_database = $request->get('DB_DATABASE');
        $db_username = $request->get('DB_USERNAME');
        $db_password = $request->get('DB_PASSWORD');

        if (!$dot_env_file_content = file_get_contents(DOTENV_LOCATION)) {
            return Response::make(DOTENV_READ_ERROR, 500);
        }

        $dot_env_file_content = preg_replace('/^DB_HOST=.*$/m', 'DB_HOST='.$db_host, $dot_env_file_content);
        $dot_env_file_content = preg_replace('/^DB_PORT=.*$/m', 'DB_PORT='.$db_port, $dot_env_file_content);
        $dot_env_file_content = preg_replace('/^DB_DATABASE=.*$/m', 'DB_DATABASE='.$db_database, $dot_env_file_content);
        $dot_env_file_content = preg_replace('/^DB_USERNAME=.*$/m', 'DB_USERNAME='.$db_username, $dot_env_file_content);
        $dot_env_file_content = preg_replace('/^DB_PASSWORD=.*$/m', 'DB_PASSWORD='.$db_password, $dot_env_file_content);

        if (!$dot_env_file_content = file_put_contents(DOTENV_LOCATION, $dot_env_file_content)) {
            return Response::make(DOTENV_WRITE_ERROR, 500);
        }

        return ALL_GOOD_MESSAGE;
    });

    Route::post('/setup/create-tables', function () {
        if (!DB::statement(<<<END
            CREATE TABLE IF NOT EXISTS husmusen_items (
                itemID        INTEGER      PRIMARY KEY,
                name          VARCHAR(128) NOT NULL,
                keywords      TEXT         DEFAULT '',
                description   TEXT         DEFAULT '',
                type          ENUM(
                    "ArtPiece",
                    "Blueprint",
                    "Book",
                    "Building",
                    "Collection",
                    "Concept",
                    "CulturalEnvironment",
                    "CulturalHeritage",
                    "Document",
                    "Exhibition",
                    "Film",
                    "Group",
                    "HistoricalEvent",
                    "InteractiveResource",
                    "PhysicalItem",
                    "Map",
                    "Organisation",
                    "Person",
                    "Photo",
                    "Sketch",
                    "Sound"
                ),
                addedAt      TIMESTAMP    DEFAULT current_timestamp,
                updatedAt    TIMESTAMP    DEFAULT current_timestamp,
                itemData     JSON         DEFAULT '{}' CHECK (JSON_VALID(itemData)),
                customData   JSON         DEFAULT '{}' CHECK (JSON_VALID(customData)),
                isExpired    BOOLEAN      DEFAULT 0,
                expireReason TEXT         DEFAULT ''
            )
            END)
        ) {
            return 'Problem vid skapande av tabell `husmusen_items`.';
        }

        if (!DB::statement(<<<END
            CREATE TABLE IF NOT EXISTS husmusen_users (
                username VARCHAR(32)  UNIQUE NOT NULL,
                password VARCHAR(128) NOT NULL,
                isAdmin  BOOLEAN      DEFAULT 0
            )
            END)
        ) {
            return 'Problem vid skapande av tabell `husmusen_users`.';
        }

        if (!DB::statement(<<<END
            CREATE TABLE IF NOT EXISTS husmusen_files (
                name        VARCHAR(128) NOT NULL,
                description TEXT         DEFAULT '',
                type        VARCHAR(128) NOT NULL,
                license     VARCHAR(128) DEFAULT 'All rights reserved',
                fileID      VARCHAR(40)  UNIQUE NOT NULL,
                addedAt     TIMESTAMP    DEFAULT current_timestamp,
                updatedAt   TIMESTAMP    DEFAULT current_timestamp,
                relatedItem INTEGER      NOT NULL
            )
            END)
        ) {
            return 'Problem vid skapande av tabell `husmusen_files`.';
        }

        if (!DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS idx_itemID ON husmusen_items (itemID)')) {
            return 'Problem vid skapande av index `idx_itemID`.';
        }

        if (!DB::statement('CREATE FULLTEXT INDEX IF NOT EXISTS idx_name ON husmusen_items (name)')) {
            return 'Problem vid skapande av index `idx_name`.';
        }

        if (!DB::statement('CREATE FULLTEXT INDEX IF NOT EXISTS idx_keywords ON husmusen_items (keywords)')) {
            return 'Problem vid skapande av index `idx_keywords`.';
        }

        if (!DB::statement('CREATE FULLTEXT INDEX IF NOT EXISTS idx_description ON husmusen_items (description)')) {
            return 'Problem vid skapande av index `idx_description`.';
        }

        if (!DB::statement('CREATE INDEX IF NOT EXISTS idx_type ON husmusen_items (type)')) {
            return 'Problem vid skapande av index `idx_type`.';
        }

        if (!DB::statement('CREATE FULLTEXT INDEX IF NOT EXISTS idx_itemData ON husmusen_items (itemData)')) {
            return 'Problem vid skapande av index `idx_itemData`.';
        }

        if (!DB::statement('CREATE FULLTEXT INDEX IF NOT EXISTS idx_customData ON husmusen_items (customData)')) {
            return 'Problem vid skapande av index `idx_customData`.';
        }

        return ALL_GOOD_MESSAGE;
    });

    Route::post('/setup/done', function () {
        if (!$dot_env_file_content = file_get_contents(DOTENV_LOCATION)) {
            return Response::make(DOTENV_READ_ERROR, 500);
        }

        $dot_env_file_content = preg_replace('/^APP_ENV=.*$/m', 'APP_ENV=production', $dot_env_file_content);
        $dot_env_file_content = preg_replace('/^APP_DEBUG=.*$/m', 'APP_DEBUG=false', $dot_env_file_content);

        if (!$dot_env_file_content = file_put_contents(DOTENV_LOCATION, $dot_env_file_content)) {
            return Response::make(DOTENV_WRITE_ERROR, 500);
        }

        return ALL_GOOD_MESSAGE;
    });
}
