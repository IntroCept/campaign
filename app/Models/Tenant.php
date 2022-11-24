<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $connection = 'master';

    protected $table = 'tenants';

    public const MORPH_ALIAS = 'tenant';
}