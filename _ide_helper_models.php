<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string|null $phone
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DialogSession|null $dialogSession
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Viewing> $viewings
 * @property-read int|null $viewings_count
 * @method static \Database\Factories\ClientFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUserId($value)
 */
	final class Client extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $client_id
 * @property int $current_intent
 * @property array<array-key, mixed>|null $context_data
 * @property string|null $last_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client|null $client
 * @method static \Database\Factories\DialogSessionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DialogSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DialogSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DialogSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DialogSession whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DialogSession whereContextData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DialogSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DialogSession whereCurrentIntent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DialogSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DialogSession whereLastMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DialogSession whereUpdatedAt($value)
 */
	final class DialogSession extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $location
 * @property int $price
 * @property \Domain\Property\TypesEnum $type
 * @property int $availability_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Viewing> $viewings
 * @property-read int|null $viewings_count
 * @method static \Database\Factories\PropertyFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereAvailabilityStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereUpdatedAt($value)
 */
	final class Property extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	final class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $client_id
 * @property int $property_id
 * @property \Illuminate\Support\Carbon $scheduled_at
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client|null $client
 * @property-read \App\Models\Property|null $property
 * @method static \Database\Factories\ViewingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Viewing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Viewing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Viewing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Viewing whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Viewing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Viewing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Viewing wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Viewing whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Viewing whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Viewing whereUpdatedAt($value)
 */
	final class Viewing extends \Eloquent {}
}

