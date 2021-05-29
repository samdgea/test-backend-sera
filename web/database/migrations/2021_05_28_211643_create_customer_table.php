<?php

use Illuminate\Database\Migrations\Migration;
use Jenssegers\Mongodb\Schema\Blueprint;

class CreateCustomerTable extends Migration
{
    protected $connection = 'mongodb';
    public function up()
    {
        Schema::connection($this->connection)
                ->create('customers', function (Blueprint $collection) {
                        $collection->id();
                        $collection->string('first_name');
                        $collection->string('last_name');
                        $collection->string('email_address');
                        $collection->string('phone_number');
                });
    }

    public function down()
    {
        Schema::connection($this->connection)
                ->table('customers', function (Blueprint $collecion) {
                    $collecion->drop();
                });
    }
}
