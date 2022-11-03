<?php

namespace App\Console\Commands;

use App\Models\User;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(private Client $elasticsearch){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        foreach (User::query()->cursor() as $user)
        {
            try {
                $this->elasticsearch->index([
                    'index' => $user->getSearchIndex(),
                    'type' => $user->getSearchType(),
                    'id' => $user->getKey(),
                    'body' => $user->toSearchArray(),
                ]);
            } catch (\Throwable $e) {
                $this->info($e->getMessage());
                return 0;
            }
            $this->output->write('.');
        }
        $this->info('\nDone!');
        return 1;
    }
}
