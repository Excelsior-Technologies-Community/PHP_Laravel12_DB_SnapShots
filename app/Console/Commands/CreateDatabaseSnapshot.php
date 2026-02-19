<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\DbSnapshots\SnapshotFactory;

class CreateDatabaseSnapshot extends Command
{
    protected $signature = 'snapshot:create 
                            {name? : The name of the snapshot}
                            {--connection= : The database connection to snapshot}
                            {--compress : Compress the snapshot}';

    protected $description = 'Create a database snapshot';

    protected $snapshotFactory;

    public function __construct(SnapshotFactory $snapshotFactory)
    {
        parent::__construct();
        $this->snapshotFactory = $snapshotFactory;
    }

    public function handle()
    {
        $name = $this->argument('name') ?? 'snapshot-' . now()->format('Y-m-d-H-i-s');
        $connection = $this->option('connection') ?? config('database.default');
        $compress = $this->option('compress') ?? true;

        $this->info("Creating snapshot: {$name}");

        try {
            $this->snapshotFactory->create($name, $connection, $compress);
            $this->info("Snapshot created successfully!");
        } catch (\Exception $e) {
            $this->error("Failed to create snapshot: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}