<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class StoreQuranDetailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'composer:quran-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store all quran information keeping';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        for ($i = 1; $i <= 30; ++$i) {
            $response = Http::get('http://api.alquran.cloud/v1/juz/' . $i . '/ar.asad');

            if ($response) {

                /*
                 * Juzs
                 * */
                DB::table('juzs')->insert([
                    'id' => $i,
                    'number' => $response['data']['number'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                /*
                 * Surahs
                 * */
                foreach ($response['data']['surahs'] as $surah){
                    DB::table('surahs')->insert([
                        'number' => $surah['number'],
                        'name' => $surah['name'],
                        'englishName' => $surah['englishName'],
                        'englishNameTranslation' => $surah['englishNameTranslation'],
                        'revelationType' => $surah['revelationType'],
                        'numberOfAyahs' => $surah['numberOfAyahs'],
                        'juz_id' => $i,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }

                /*
                 * Ayahs
                 * */
                foreach ($response['data']['ayahs'] as $ayah){
                    DB::table('ayahs')->insert([
                        'number' => $ayah['number'],
                        'text' => $ayah['text'],
                        'numberInSurah' => $ayah['numberInSurah'],
                        'manzil' => $ayah['manzil'],
                        'page' => $ayah['page'],
                        'ruku' => $ayah['ruku'],
                        'hizbQuarter' => $ayah['hizbQuarter'],
                        'sajda' => $ayah['sajda'],
                        'surah_id' => $ayah['surah']['number'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }

                echo 'Done!';
            }else {
                echo 'Failed';
            }
        }
    }
}
