<?php

namespace App\Http\Livewire\Courses;

use App\Models\Course;
use Livewire\Component;
use App\Models\CourseItem;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseEdit extends Component
{
    use WithFileUploads;
    
    public $course;

    public $inputCourse;
    public $inputCourseItems;

    protected $rules = [
        'inputCourse.title' => 'required',
        'inputCourse.thumbnail' => 'nullable|image|max:5024',
        'inputCourse.price' => 'required|numeric|min:1',
        'inputCourseItems.*.original_id' => 'nullable',
        'inputCourseItems.*.title' => 'required',
        'inputCourseItems.*.video' => 'nullable|max:1002400',
        // 'inputCourseItems.*.video' => 'nullable|mimetypes:video/x-ms-asf,video/x-matroska,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi|max:1002400',
        
    ];

    public function mount(Course $course) {
        $this->course = $course;

        $this->fill([
            'inputCourse' => [
                'title' => $course->title,
                'thumbnail' => '',
                'description' => $course->description,
                'price' => $course->price
            ]
        ]);

        $courseItems = CourseItem::where('course_id', $course->id)->get();

        if (count($courseItems) <= 0) {
            $this->fill([
                'inputCourseItems' => [[
                    'original_id' => '',
                    'title' => '',
                    'video' => '',
                    'original_video' => ''
                ]]
            ]);
        } else {
            $itemsData = [];
            foreach ($courseItems as $item) {
                $itemsData[] = [
                    'original_id' => $item->id,
                    'title' => $item->title,
                    'video' => '',
                    'original_video' => $item->video
                ];
            }

            $this->fill([ 'inputCourseItems' => $itemsData ]);
        }
    }

    public function render()
    {
        return view('livewire.courses.course-edit');
    }

    public function newItem() {
        array_push($this->inputCourseItems, [
            'original_id' => '',
            'title' => '',
            'video' => '',
            'original_video' => ''
        ]);
    }

    public function removeItem(int $key) {
        if (count($this->inputCourseItems) > 1)
            unset($this->inputCourseItems[$key]);
    }

    public function update() {
        // dd($this->inputCourseItems);
        $this->validate();

        // upload photo
        $imageName = $this->course->thumbnail;
        if (!empty($this->inputCourse['thumbnail'])) {
            if(Storage::exists('courses/thumbnails/'.$this->course->thumbnail))
                Storage::delete('courses/thumbnails/'.$this->course->thumbnail);

            $imageName = 'thumbnail-'.Auth::user()->username.'-'.time().'.'.$this->inputCourse['thumbnail']->extension();
            $this->inputCourse['thumbnail']->storeAs('courses/thumbnails', $imageName);
        }

        // update course
        $inputCourse = $this->inputCourse;
        $inputCourse = array_merge($inputCourse, [
            'thumbnail' => $imageName,
            'slug' => Str::slug($inputCourse['title'])
        ]);

        $course = Course::where('id', $this->course->id)->first();
        $course->update($inputCourse);

        $this->storeItems($course);
        $this->updateDuration($course);

        return redirect()->route('class.detail', ['course' => $course]);
    }

    protected function storeItems($course) {
        $courseItems = $this->inputCourseItems;
        $createdData = [];
        $updatedIds = [];

        foreach ($courseItems as $key => $item) {
            if (!empty($item['video'])) {
                $videoName = 'video-'.Auth::user()->username.'-'.$key.time().'.'.$item['video']->extension();
                $item['video']->storeAs('courses/videos', $videoName);

                $item['video'] = $videoName;
            }

            if (empty($item['original_id'])) { // create new
                $createdData[] = [
                    'course_id' => $course->id,
                    'title' => $item['title'],
                    'video' => $item['video']
                ];
            } else {
                $updData = [
                    'title' => $item['title']
                ];

                if (!empty($item['video']))
                    $updData['video'] = $item['video'];

                $course->courseItems()
                    ->where('id', $item['original_id'])
                    ->update($updData);

                $updatedIds[] = $item['original_id'];
            }
        }

        $excludedItems = $course->courseItems()->whereNotIn('id', $updatedIds)->get();

        foreach ($excludedItems as $item)  {
            if(Storage::exists('courses/videos/'.$item->video))
                Storage::delete('courses/videos/'.$item->video);

            $item->delete();
        }

        if (count($createdData) > 0)
            $course->courseItems()->insert($createdData);
    }

    protected function updateDuration($course) {
        $getID3 = new \getID3;
        $totalDuration = 0;

        $courseItems = CourseItem::where('course_id', $course->id)->get();

        foreach ($courseItems as $item) {
            // get video duration
            $file = $getID3->analyze(storage_path('app/public/courses/videos/'.$item->video));
            if (isset($file['playtime_seconds']))
                $totalDuration += $file['playtime_seconds'];
        }

        $dt = new \DateTime('now', new \DateTimeZone('UTC')); 
        $dt->setTimestamp($totalDuration);
        $duration = $dt->format('H:i:s');
        $course->update([ 'duration' => $duration ]);
    }
}
