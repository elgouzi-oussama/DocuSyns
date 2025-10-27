<?php

namespace App\Helpers;

use App\Models\License;
use App\Models\LicensesType;

class SystemHelper
{
    public static function getServerId(): string
    {
        $components = [
            php_uname('n'), // Hostname
            php_uname('s'), // OS name
            php_uname('r'), // OS release
        ];

        return hash('sha256', implode('', $components));
    }
    public function hasLicenseType(string $typeName): bool
    {
        $serverId = $this->getServerId();
        $license = License::where('server_id', $serverId)->first();

        if (!$license || $license->akaeay_ === null) {
            return false;
        }

        $licenseType = LicensesType::where('_name', $typeName)->first();

        return $licenseType && $license->akaeay_ === $licenseType->akaeay_;
    }
}
