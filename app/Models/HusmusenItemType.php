<?php

namespace App\Models;

/**
 * Represents all possible item types.
 *
 * This is not stored in the database; it is just a model!
 */
enum HusmusenItemType: string
{
    case ArtPiece = 'ArtPiece';
    case Blueprint = 'Blueprint';
    case Book = 'Book';
    case Building = 'Building';
    case Collection = 'Collection';
    case Concept = 'Concept';
    case CulturalEnvironment = 'CulturalEnvironment';
    case CulturalHeritage = 'CulturalHeritage';
    case Document = 'Document';
    case Exhibition = 'Exhibition';
    case Film = 'Film';
    case Group = 'Group';
    case HistoricalEvent = 'HistoricalEvent';
    case InteractiveResource = 'InteractiveResource';
    case Map = 'Map';
    case Organisation = 'Organisation';
    case Person = 'Person';
    case Photo = 'Photo';
    case PhysicalItem = 'PhysicalItem';
    case Sketch = 'Sketch';
    case Sound = 'Sound';

    /**
     * Gets all types as string representations in an array.
     *
     * @return string[]
     */
    public static function get_as_strings(): array
    {
        return array_map(fn (HusmusenItemType $type) => $type->name, HusmusenItemType::cases());
    }
}
