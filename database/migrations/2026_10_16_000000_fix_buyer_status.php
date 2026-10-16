<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Form daftar lama memberi pembeli status "rejected"; pembeli seharusnya langsung aktif
    public function up()
    {
        DB::table('users')->where('role', 'user')->where('status', 'rejected')->update(['status' => 'approved']);
    }

    public function down()
    {
        //
    }
};
