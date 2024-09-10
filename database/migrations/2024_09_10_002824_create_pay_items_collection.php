<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePayItemsCollection extends Migration
{
    public function up()
    {
        $collections = DB::connection('mongodb')->getMongoDB()->listCollections();
        $exists = false;

        foreach ($collections as $collection) {
            if ($collection->getName() === 'pay_items') {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            DB::connection('mongodb')->getMongoDB()->createCollection('pay_items');
        }

        DB::connection('mongodb')->getCollection('pay_items')->createIndex(['external_id' => 1]);
        DB::connection('mongodb')->getCollection('pay_items')->createIndex(['user_id' => 1]);
        DB::connection('mongodb')->getCollection('pay_items')->createIndex(['business_id' => 1]);
        DB::connection('mongodb')->getCollection('pay_items')->createIndex(['pay_date' => 1]);
    }

    public function down()
    {
        DB::connection('mongodb')->getMongoDB()->dropCollection('pay_items');
    }
}
