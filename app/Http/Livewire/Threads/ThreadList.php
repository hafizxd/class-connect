<?php

namespace App\Http\Livewire\Threads;

use App\Models\Thread;
use App\Models\Forum;
use Livewire\Component;
use Livewire\WithPagination;

class ThreadList extends Component
{
    use WithPagination;

    public $order = 'most-discussed';
    public $search = '';

    protected $listeners = ['thread-added' => '$refresh'];

    public Forum $forum;

    public function mount(Forum $forum) {
        $this->forum = $forum;
    }

    public function render()
    {
        $threads = Thread::with(['author'])->where('forum_id', $this->forum->id)->withCount(['likes', 'replies', 'nestedReplies']);

        if (isset($this->search)) {
            $threads->where(function ($query) {
                $query->where('title', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('body', 'LIKE', '%'.$this->search.'%');
            });
        }
        
        switch($this->order) {
            case 'most-discussed':
                $threads->orderBy('replies_count', 'desc');
                break;
            
            case 'most-liked':
                $threads->orderBy('likes_count', 'desc');
                break;

            case 'newest':
                $threads->orderBy('created_at', 'desc');
                break;
        }

        $threads = $threads->paginate(10);

        return view('livewire.threads.thread-list', [
            'threads' => $threads,
            'forum' => $this->forum
        ]);
    }
}
