<?php

namespace App\Http\Livewire\Keeps;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class CreateLivewire extends Component
{
    public $selectedStartJuz;
    protected $juzs_response;
    protected $data;
    public $fileName = 'quran';
    public $extension = '.json';
    public $group;
    public $student;


    public function mount($student, $group)
    {
        $this->group = $group;
        $this->student = $student;
        $this->selectedStartJuz = 30;
        $fullFilePath = $this->fileName . '' . $this->selectedStartJuz . '' . $this->extension;

        /*
         * Is file exists ?!
         * */
        if (file_exists(storage_path($fullFilePath))) {
            /*
             * Is not the same request data ?!
             * */
            $str = json_decode(file_get_contents(storage_path($fullFilePath)));
            if (!$str) {
                $this->juzs_response = Http::get('http://api.alquran.cloud/v1/juz/' . $this->selectedStartJuz . '/en.asad');
                file_put_contents(storage_path($fullFilePath), $this->juzs_response);
            }
        } else {
            $this->juzs_response = Http::get('http://api.alquran.cloud/v1/juz/' . $this->selectedStartJuz . '/en.asad');
            file_put_contents(storage_path($fullFilePath), $this->juzs_response);
        }


        $this->data = json_decode(file_get_contents(storage_path($fullFilePath)));
    }


    public function render()
    {
        $this->getReqestedInformation();

        return view('livewire.keeps.create-livewire', [
            'juzs_response' => $this->data->data,
        ]);
    }



    public function getReqestedInformation () {
        $fullFilePath = $this->fileName . '' . $this->selectedStartJuz . '' . $this->extension;

        /*
         * Is file exists ?!
         * */
        if (file_exists(storage_path($fullFilePath))) {

            /*
             * Is not the same request data ?!
             * */
            $str = json_decode(file_get_contents(storage_path($fullFilePath)));
            if (!$str) {
                $this->juzs_response = Http::get('http://api.alquran.cloud/v1/juz/' . $this->selectedStartJuz . '/ar.asad');
                file_put_contents(storage_path($fullFilePath), $this->juzs_response);
            }
        } else {
            $this->juzs_response = Http::get('http://api.alquran.cloud/v1/juz/' . $this->selectedStartJuz . '/en.asad');
            file_put_contents(storage_path($fullFilePath), $this->juzs_response);
        }


        $this->data = json_decode(file_get_contents(storage_path($fullFilePath)));
    }
}
