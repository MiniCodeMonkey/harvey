<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use TwitterStreamingApi;
use App\Message;
use Carbon\Carbon;
use PDOException;

class FetchTweetsCommand extends Command
{

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

        TwitterStreamingApi::publicStream()
            ->whenHears(config('twitter.search_terms'), function ($status) {
                // Skip retweets
                $isRetweet = ($status['retweeted_status'] ?? false) || mb_strpos($status['text'], 'RT ') === 0;
                if ($isRetweet && !Str::contains($status['text'], '#harveysos')) {
                    return; // Skip retweets that does not contain the #harveysos hashtag
                }

                try {
                    $message = new Message();
                    $message->twitter_id = $status['id_str'];
                    $message->message_created = Carbon::parse($status['created_at']);
                    $message->message_text = $status['text'];
                    $message->user_id = $status['user']['id_str'];
                    $message->user_handle = $status['user']['screen_name'];
                    $message->user_name = $status['user']['name'] ?? '';
                    $message->user_location = $status['user']['location'] ?? '';
                    $message->save();
                } catch (PDOException $e) {
                    $this->info('Exception: ' . $e->getMessage());
                    // Probably duplicate key
                }

                $this->info('1 new message: ' . $status['user']['screen_name'] . '/status/' .  $status['id_str']);
            })
            ->startListening();
    }
}
