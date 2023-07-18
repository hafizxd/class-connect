<?php

namespace App\Http\Livewire\Forums;

use App\Models\Forum;
use Livewire\Component;
use Livewire\WithPagination;

class ForumList extends Component
{
    use WithPagination;

    public $order = 'most-threads';
    public $search = '';

    protected $listeners = ['forum-added' => '$refresh'];

    public function render()
    {
        $forums = Forum::with('threads.replies', 'threads.nestedReplies')->withCount('threads');

        if (isset($this->search)) {
            $forums->where(function ($query) {
                $query->where('name', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('description', 'LIKE', '%'.$this->search.'%');
            });
        }

        switch($this->order) {
            case 'most-threads':
                $forums->orderBy('threads_count', 'desc');
                break;
            
            case 'a_z':
                $forums->orderBy('name', 'asc');
                break;

            case 'newest':
                $forums->orderBy('created_at', 'desc');
                break;
        }

        $forums = $forums->paginate(10);

        return view('livewire.forums.forum-list', [
            'forums' => $forums
        ]);
    }
}
