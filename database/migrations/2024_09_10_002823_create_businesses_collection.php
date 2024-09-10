<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateBusinessesCollection extends Migration
{
    public function up()
    {
        $collections = DB::connection('mongodb')->getMongoDB()->listCollections();
        $exists = false;

        foreach ($collections as $collection) {
            if ($collection->getName() === 'businesses') {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            DB::connection('mongodb')->getMongoDB()->createCollection('businesses');
        }

        DB::connection('mongodb')->getCollection('businesses')->createIndex(['external_id' => 1], ['unique' => true]);
        DB::connection('mongodb')->getCollection('businesses')->createIndex(['enabled' => 1]);
    }

    public function down()
    {
        DB::connection('mongodb')->getMongoDB()->dropCollection('businesses');
    }
}
