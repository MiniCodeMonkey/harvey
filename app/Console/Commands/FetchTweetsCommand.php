<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Twitter;
use App\Message;
use Carbon\Carbon;
use PDOException;

class FetchTweetsCommand extends Command
{
    const SEARCH_TERMS = [
        '#NeedWaterRescue',
        '#HarveySOS',
        '@HoustonTX',
        '@SylvesterTurner',
        '@houstonpolice',
        '@cohoustonfire',
        '@HoustonOEM',
        '@KHOU',
        '@SheriffEd_HCSO',
        '@TxNationalGuard',
        '@USCG',
        '@khou',
        '@abc13houston',
        '@fox26houston',
        '@GHC911',
        '#waterrescue',
        '#HarveyRescue'
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:tweets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches tweets';

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
     * @return mixed
     */
    public function handle()
    {        
        $query = implode(' OR ', self::SEARCH_TERMS);
        $query .= ' -filter:retweets'; // Filter out retweets

        // q: 500 characters max
        // count: max 100 results
        // https://dev.twitter.com/rest/reference/get/search/tweets
        
        $data = Twitter::getSearch([
            'q' => $query,
            'result_type' => 'recent',
            'count' => 100
        ]);

        $insertsCount = 0;

        foreach ($data->statuses as $status) {
            try {
                $message = new Message();
                $message->twitter_id = $status->id_str;
                $message->message_created = Carbon::parse($status->created_at);
                $message->message_text = $status->text;
                $message->user_id = $status->user->id_str;
                $message->user_handle = $status->user->screen_name;
                $message->user_name = $status->user->name;
                $message->user_location = $status->user->location;
                $message->save();
                $insertsCount++;
            } catch (PDOException $e) {
                // Probably duplicate key
            }
        }

        $this->info($insertsCount . ' new messages');
    }
}
