<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Database\DatabaseManager;

class TenantDatabaseConnector
{
    protected string $connection = 'tenant';

    protected string $db_name;
    protected string $db_username;
    protected string $db_password;
    protected Tenant $tenant;

    public function __construct(public readonly DatabaseManager $databaseManager)
    {
    }

    public function connect(string $subdomain)
    {
        $this->tenant = Tenant::query()->where('subdomain', $subdomain)->first();

        $this->initDB();
        $this->connectTenantDB();

        return $this->tenant;
    }

    public function connectTenantDB(): void
    {
        $newConnection = config('database.connections.' . $this->connection);
        $newConnection['database'] = $this->db_name;
        $newConnection['username'] = $this->db_username;
        $newConnection['password'] = $this->db_password;
        config()->set('database.connections.' . $this->connection, $newConnection);
        $this->databaseManager->reconnect($this->connection);
        $this->changeConnection($this->connection);
    }


    public function initDB(): void
    {
        $setting = config('database.connections.' . $this->connection);

        $this->db_username = $setting['username'];
        $this->db_password = $setting['password'];

        $this->db_name = $this->tenant->db_name;
    }

    public function changeConnection($connectionName): void
    {
        config()->set('database.default', $connectionName);
    }
}