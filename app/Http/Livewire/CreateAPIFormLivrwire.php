<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;

class CreateAPIFormLivrwire extends Component
{
    public $uuid;
    public $key;
    public $secret;

    public function render()
    {
        $this->uuid = Str::uuid()->toString();

        $randomString = Str::random(32);
        $this->key = sprintf(
            '%08s-%04s-%04s-%04s-%012s',
            substr($randomString, 0, 8),
            substr($randomString, 8, 4),
            substr($randomString, 12, 4),
            substr($randomString, 16, 4),
            substr($randomString, 20)
        );

        $randomString = Str::random(32);
        $this->secret = sprintf(
            '%08s-%04s-%04s-%04s-%012s',
            substr($randomString, 0, 8),
            substr($randomString, 8, 4),
            substr($randomString, 12, 4),
            substr($randomString, 16, 4),
            substr($randomString, 20)
        );

        return view('livewire.create-a-p-i-form-livrwire');
    }
}
