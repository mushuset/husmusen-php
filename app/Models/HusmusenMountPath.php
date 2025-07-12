<?php

namespace App\Models;

/**
 * This is just a wrapper model around getting the HUSMUSEN_MOUNT_PATH from the .env.
 */
class HusmusenMountPath
{
    /**
     * Get the HUSMUSEN_MOUNT_PATH from the .env file, if not present, default to root, "/".
     */
    public static function get_husmusen_mount_path()
    {
        return env('HUSMUSEN_MOUNT_PATH', '');
    }
}
