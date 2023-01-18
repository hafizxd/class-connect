<div class="py-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <form wire:submit.prevent="store" method="POST" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div>
                    <h1 class="text-3xl text-white mb-3">Class</h1>
        
                    <x-card class="flex flex-col sm:flex-row sm:justify-between sm:gap-10 p-6">
                        <div class="w-full">
                            <div class="mb-5">
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input wire:model.defer="course.title" id="title" class="block mt-1 w-full" type="text" required autofocus />
                                <x-input-error :messages="$errors->get('course.title')" class="mt-2" />
                            </div>
            
                            <div class="mb-5">
                                <x-input-label for="thumbnail" :value="__('Thumbnail')" />
                                <input type="file" wire:model.defer="course.thumbnail" id="thumbnail" class="mt-1" required>
                                @if ($course['thumbnail'])
                                    <img src="{{ $course['thumbnail']->temporaryUrl() }}">
                                @endif
                                <x-input-error :messages="$errors->get('course.thumbnail')" class="mt-2" />
                            </div>

                            <div class="mb-5">
                                <x-input-label for="price" :value="__('Price')" />
                                <x-text-input wire:model.defer="course.price" id="price" class="block mt-1 w-full" type="number" required />
                                <x-input-error :messages="$errors->get('course.price')" class="mt-2" />
                            </div>
            
                            <div class="mb-5">
                                <x-input-label for="description" :value="__('Description (optional)')" />
                                <x-textarea-input rows="5" wire:model.defer="course.description" id="description" class="block mt-1 w-full" type="text" />
                                <x-input-error :messages="$errors->get('course.description')" class="mt-2" />
                            </div>
                        </div>
                    </x-card>
                </div>
        
                <div x-data="videoItem()">
                    <h1 class="text-3xl text-white mb-3">Class Videos</h1>
        
                    @foreach ($courseItems as $key => $item)
                        <x-card wire:key="{{ $key }}" class="flex flex-col sm:flex-row sm:justify-between sm:gap-10 p-6 mb-6">
                            <div>
                                <div class="mb-5">
                                    <x-input-label for="itemTitle_{{$key}}" :value="__('Video Title')" />
                                    <x-text-input wire:model.defer="courseItems.{{$key}}.title" id="itemTitle_{{$key}}" class="block mt-1 w-full" type="text"/>
                                    @error('courseItems.'.$key.'.title')
                                        <span class="block text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
        
                                <div class="mb-5">
                                    <x-input-label for="itemVideo_{{$key}}" :value="__('Video')" />
                                    <input type="file" wire:model.defer="courseItems.{{$key}}.video" id="itemVideo_{{$key}}" accept="video/*" @change="showPreview(event, '{{ $key }}')">
                                    @error('courseItems.'.$key.'.video')
                                        <span class="block text-sm text-red-600 dark:text-red-400 space-y-1 mt-2">{{ $message }}</span>
                                    @enderror

                                    <div class="text-white my-2" wire:loading wire:target="inputCourseItems.{{$key}}.video">Uploading...</div>

                                    <video wire:ignore id="itemPreview_{{$key}}" controls class="w-full h-full hidden">
                                    </video>
                                </div>
                            </div>
        
                            <div class="self-end sm:self-start">
                                <x-danger-button wire:click="removeItem({{$key}})" type="button">
                                    <i class="uil uil-trash-alt"></i>
                                </x-danger-button>
                            </div>
                        </x-card>
                    @endforeach
        
                    <div class="flex justify-end">
                        <x-primary-button wire:click="newItem()" type="button">
                            <i class="uil uil-plus"></i> New Item
                        </x-primary-button>
                    </div>
                </div>
            </div>

            <x-primary-button class="my-10 py-5 w-full">
                Create Class
                <span wire:loading>(Saving...)</span>
            </x-primary-button>
        </form>
    </div>
</div>

<script>
    function videoItem() {
        return {
            showPreview(event, key) {
                console.log(key);
                if (event.target.files.length > 0) {
                    let imageFile = event.target.files[0];

                    let reader = new FileReader();
                    reader.onload = function (e) {
                        let preview = document.querySelector("#itemPreview_" + key);
                        preview.src = reader.result;

                        let prevParent = document.querySelector("#itemPreview_" + key);
                        prevParent.classList.remove('hidden');
                        prevParent.classList.add('block');
                    }
                    reader.readAsDataURL(event.target.files[0]);
                }
            }
        }
    }
</script>