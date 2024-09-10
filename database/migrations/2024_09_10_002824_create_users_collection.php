<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUsersCollection extends Migration
{
    public function up()
    {
        $collections = DB::connection('mongodb')->getMongoDB()->listCollections();
        $exists = false;

        foreach ($collections as $collection) {
            if ($collection->getName() === 'users') {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            DB::connection('mongodb')->getMongoDB()->createCollection('users');
        }

        DB::connection('mongodb')->getCollection('users')->createIndex(['external_id' => 1], ['unique' => true]);
        DB::connection('mongodb')->getCollection('users')->createIndex(['email' => 1], ['unique' => true]);
    }

    public function down()
    {
        DB::connection('mongodb')->getMongoDB()->dropCollection('users');
    }
}
