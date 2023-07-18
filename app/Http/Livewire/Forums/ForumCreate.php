<?php

namespace App\Http\Livewire\Forums;

use App\Models\Forum;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class ForumCreate extends Component
{
    use WithFileUploads;

    public $name;
    public $image;
    public $category;
    public $description;

    protected $listeners = ['close-card' => 'closeCardForAlpine'];

    public function store()
    {
        $this->validate([
            'name' => 'required|max:255|unique:forums',
            'image' => 'required|image|max:5024',
            'category' => 'required|array'
        ]);
        
        $slug = Str::slug($this->name);

        $imageName = $slug.'-'.time().'.'.$this->image->extension();
        $this->image->storeAs('forums', $imageName);

        $forum = Forum::create([
            'name' => $this->name,
            'slug' => $slug,
            'image' => $imageName,
            'description' => $this->description
        ]);

        $forum->categories()->attach($this->category);

        $this->emit('forum-added');

        $this->reset('name');
        $this->reset('image');
        $this->reset('description');
        $this->reset('category');

        $this->closeCardForAlpine();
    }

    public function render()
    {
        $categories = Category::orderBy('name', 'desc')->get();

        return view('livewire.forums.forum-create', compact('categories'));
    }

    public function closeCardForAlpine()
    {
        $this->dispatchBrowserEvent('card-closed', ['openCreate' => false]);
    }
}
