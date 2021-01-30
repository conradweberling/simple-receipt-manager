<?php

namespace App\Console\Commands;

use App\Models\Receipt;
use Illuminate\Console\Command;

class Destroy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'destroy:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Destroy deleted entries';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        Receipt::destroyAll();

        return 0;

    }
}
