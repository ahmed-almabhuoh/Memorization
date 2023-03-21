<?php

namespace App\Jobs;

use App\Models\Question;
use App\Models\Test;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CreateTestQuestiosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $test;

    /**
     * Create a new job instance.
     */
    public function __construct(Test $test)
    {
        //
        $this->test = $test;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $from_juz = $this->test->from;
        $to_juz = $this->test->to;
        $from_nos = [];
        $to_nos = [];
        $prefix_file_name = 'quran';
        $extention_file_name = '.json';
        $test = [];

        /*
         * From Uploaded Files
         * */
        if (!file_exists(storage_path('quran' . $from_juz . '.json'))) {
            $response = Http::get('https://api.alquran.cloud/v1/juz/' . $from_juz . '/ar.asad');
            file_put_contents(storage_path($prefix_file_name . '' . $from_juz . '' . $extention_file_name), $response);
        }
        if (!file_exists(storage_path('quran' . $to_juz . '.json'))) {
            $response = Http::get('https://api.alquran.cloud/v1/juz/' . $to_juz . '/ar.asad');
            file_put_contents(storage_path($prefix_file_name . '' . $to_juz . '' . $extention_file_name), $response);
        }
        $from_file = file_get_contents(storage_path('quran' . $from_juz . '.json'));
        $to_file = file_get_contents(storage_path('quran' . $to_juz . '.json'));
        $from_file_ayahs = json_decode($from_file)->data->ayahs;
        $to_file_ayahs = json_decode($to_file)->data->ayahs;

        /*
         * Start processing
         * */
        foreach ($from_file_ayahs as $ayah) {
            $from_nos[] = $ayah->number;
        }

        foreach ($to_file_ayahs as $ayah) {
            $to_nos[] = $ayah->number;
        }

        shuffle($from_nos);
        shuffle($to_nos);
        $random_nos_from = array_slice($from_nos, 0, 3);
        $random_nos_to = array_slice($to_nos, 0, 3);
        sort($random_nos_from);
        sort($random_nos_to);


        foreach ($from_file_ayahs as $ayah_from) {
            for ($i = 0; $i < count($random_nos_from); ++$i) {
                if ($ayah_from->number == $random_nos_from[$i]) {
                    $test[] = $ayah;
                }
            }
        }
        foreach ($to_file_ayahs as $ayah) {
            for ($i = 0; $i < count($random_nos_to); ++$i) {
                if ($ayah->number == $random_nos_to[$i]) {
                    $test[] = $ayah;
                }
            }
        }

        for ($i = 0; $i < count($test); ++$i) {
            $question = new Question();
            $question->ayah = json_encode($test[$i]);
            $question->test_id = $this->test->id;
            $question->save();
        }
    }
}
