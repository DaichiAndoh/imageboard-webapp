<?php

namespace Database;

class DatabaseManager {
    protected static array $mysqliConnections = [];

    public static function getMysqliConnection(string $connectionName = 'default'): MySQLWrapper {
        if (!isset(static::$mysqliConnections[$connectionName])) {
            static::$mysqliConnections[$connectionName] = new MySQLWrapper();
        }

        return static::$mysqliConnections[$connectionName];
    }
}
