<?php

namespace App\Models;

use App\Helpers\SystemHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'server_id',
        'auasae_',
        'akaeay_',

    ];
    protected $casts = [
        'auasae_' => 'encrypted',
        'akaeay_' => 'encrypted',
    ];







    public function getFeature($key)
    {
        if (session('license_type') === 'trial') {
            if ($key === 'users') {
                return 3;
            } else {
                return 1;
            }
        }
        $licenseType = LicensesType::all()->firstWhere('akaeay_', $this->akaeay_);
        $features = json_decode($licenseType->features, true);
        return $features[$key] ?? null;
    }

    public function totalAccountsAllowed()
    {
        if (session('license_type') === 'trial') {
            return 4; // 3 users + 1 admin
        }
        $licenseType = LicensesType::all()->firstWhere('akaeay_', $this->akaeay_);
        $features = json_decode($licenseType->features, true);
        return ($features['users'] ?? 0) + ($features['admins'] ?? 0);
    }
}
