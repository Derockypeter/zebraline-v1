<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Laravel\Passport\HasApiTokens;

/**
 * @method static find(mixed $searchTenant)
 * @method static where(mixed $ser)
 */
class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasApiTokens;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'user_id',
            'template_id',
            'description',
            'domainName',
            'domainConnect',
            'customEmail',
            'site_data',
            'storetype_id',
        ];
    }

    // public function getIncrementing()
    // {
    //     return true;
    // }

    public function user()
    {
        // return $this->belongsTo('App\Models\User', 'user_id', 'id')->select('firstname', 'lastname', 'fb_token', 'id', 'nickname', 'profilePicUrl');
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    // public function template()
    // {
    //     // return $this->belongsTo('App\Models\User', 'user_id', 'id')->select('firstname', 'lastname', 'fb_token', 'id', 'nickname', 'profilePicUrl');
    //     return $this->belongsTo('App\Models\Template', 'template_id', 'id');
    // }

    // public function store_type() {
    //     return $this->belongsTo(StoreType::class);
    // }
}
