<?php

namespace App\Http\Livewire\Side;

use App\Models\Forum;
use Livewire\Component;

class ForumDescription extends Component
{
    public $forum;

    public function render()
    {
        return view('livewire.side.forum-description', ['forum' => $this->forum]);
    }
}
