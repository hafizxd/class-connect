<?php

namespace App\Http\Livewire\Threads;

use App\Models\Thread;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ThreadDetail extends Component
{
    public $title;
    public $body;

    public Thread $thread;

    protected $listeners = ['credit-changed' => '$refresh'];

    public function mount(Thread $thread)
    {
        $this->thread = $thread;
        $this->title = $thread->title;
        $this->body = $thread->body;
    }

    public function render()
    {
        return view('livewire.threads.thread-detail');
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|max:255|unique:'.Thread::class.',title,'.$this->thread->id,
            'body' => 'required'
        ]);

        $slug = Str::slug($this->title);
        
        $this->thread->update([
            'title' => $this->title,
            'body' => $this->body,
            'slug' => $slug
        ]);

        $this->dispatchBrowserEvent('card-closed', ['openEdit' => false]);
    }

    public function destroy()
    {
        Thread::destroy($this->thread->id);

        return redirect()->route('thread.index', 1);
    }
}
