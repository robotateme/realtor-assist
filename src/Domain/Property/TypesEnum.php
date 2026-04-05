<?php

namespace Domain\Property;

enum TypesEnum: string {
    case APARTMENT = 'apartment';
    case HOUSE = 'house';
    case TOWNHOUSE = 'townhouse';
    case DUPLEX = 'duplex';
    case STUDIO = 'studio';
    case APARTMENTS = 'apartments';
    case OFFICE = 'office';
    case RETAIL = 'retail';
    case WAREHOUSE = 'warehouse';
    case LAND = 'land';
    case GARAGE = 'garage';
    case HOTEL = 'hotel';
    case INVESTMENT = 'investment';
}
