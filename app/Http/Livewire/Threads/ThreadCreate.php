<?php

namespace App\Http\Livewire\Threads;

use App\Models\Thread;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ThreadCreate extends Component
{
    public $title;
    public $body;

    public $forumId;

    protected $listeners = ['close-card' => 'closeCardForAlpine'];

    public function store()
    {
        $this->validate([
            'title' => 'required|max:255|unique:threads',
            'body' => 'required'
        ]);

        $thread = Thread::create([
            'forum_id' => $this->forumId,
            'user_id' => Auth::user()->id,
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'body' => $this->body
        ]);

        $this->emit('thread-added');

        $this->reset('title');
        $this->reset('body');

        $this->closeCardForAlpine();
    }

    public function render()
    {
        return view('livewire.threads.thread-create');
    }

    public function closeCardForAlpine()
    {
        $this->dispatchBrowserEvent('card-closed', ['openCreate' => false]);
    }
}
