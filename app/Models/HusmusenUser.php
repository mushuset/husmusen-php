<?php

namespace App\Models;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Used to get the JWT key is used to sign the JWTs so they can't be meddled with by the clients.
 */
function get_jwt_key()
{
    return config('app.key');
}

/**
 * Four hours worth of seconds; used to set expiration times for tokens.
 */
const FOUR_HOURS_AS_SECONDS = 4 * 60 * 60;

/**
 * This class represents a user stored in the database.
 *
 * @property string $username
 * @property string $password Hashed password of the user.
 * @property bool   $isAdmin
 */
class HusmusenUser extends Model
{
    use HasFactory;

    // Items are stored in the table `husmusen_items`
    protected $table = 'husmusen_users';

    // Specify primary key!
    // This is necessary only when the primary key is something other than 'id'.
    protected $primaryKey = 'username';
    protected $keyType = 'string';
    protected $fillable = ['username', 'password', 'isAdmin'];
    public $timestamps = false;

    /**
     * Create a JWT for a given user.
     * It will expire at `$valid_until` (specified as a unix epoch).
     */
    public static function get_token(HusmusenUser $user, int $valid_until): string
    {
        $payload = [
            'iss' => config('app.url'),
            'aud' => config('app.url'),
            'iat' => time(),
            'nbf' => time(),
            'exp' => $valid_until,
            'sub' => $user->username,
            'admin' => $user->isAdmin,
        ];

        return JWT::encode($payload, get_jwt_key(), 'HS256');
    }

    public static function decode_token(string $token): \stdClass
    {
        /*
         * This class represents a user stored in the database.
         *
         * @property string $sub   Username
         * @property bool   $admin
         */
        return JWT::decode($token, new Key(get_jwt_key(), 'HS256'));
    }

    /**
     * Check if the given token is valid.
     * It will return false if the token si invalid, no matter the cause of the invalidity.
     */
    public static function check_token_validity(string $token): bool
    {
        try {
            HusmusenUser::decode_token($token);

            return true;
        } catch (\LogicException $e) {
            return false;
        } catch (\UnexpectedValueException $e) {
            return false;
        }
    }
}
