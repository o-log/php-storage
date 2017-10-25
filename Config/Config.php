<?php

namespace Config;

use OLOG\Auth\AuthConfig;
use OLOG\Cache\BucketMemcache;
use OLOG\Cache\CacheConfig;
use OLOG\Cache\MemcacheServer;
use OLOG\DB\ConnectorMySQL;
use OLOG\DB\DBConfig;
use OLOG\DB\Space;
use OLOG\Storage\LocalStorage;
use OLOG\Storage\StorageConfig;
use OLOG\Storage\StorageConstants;

class Config
{
    const CONNECTOR_DEMO = 'CONNECTOR_DEMO';
    const STORAGE_DEMO = 'STORAGE_DEMO';

    static public function init(){
        ini_set('assert.exception', true);

        DBConfig::setConnector(self::CONNECTOR_DEMO, new ConnectorMySQL('127.0.0.1', 'phpstorage', 'root', '1234'));

        DBConfig::setSpace(AuthConfig::SPACE_PHPAUTH, new Space(self::CONNECTOR_DEMO, __DIR__ . '/../vendor/o-log/php-auth/db_phpauth.sql'));
        DBConfig::setSpace(StorageConstants::SPACE_PHPSTORAGE, new Space(self::CONNECTOR_DEMO, __DIR__ . '/../db_phpstorage.sql'));

        StorageConfig::setStorage(self::STORAGE_DEMO, new LocalStorage(__DIR__ . '/../public/uploaded'));

        CacheConfig::setBucket('', new BucketMemcache([new MemcacheServer('localhost', 11211)]));

        AuthConfig::setFullAccessCookieName('ksjgsgdfg');
    }
}