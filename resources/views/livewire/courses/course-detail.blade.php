<div class="py-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 grid-flow-row md:grid-flow-col md:gap-10">
            <div class="col-span-3">
                <div class="rounded-t-xl overflow-hidden max-h-96">
                    <img src="{{ asset('storage/courses/thumbnails/'.$course->thumbnail) }}" alt="" class="w-full">
                </div>

                <h1 class="my-5 text-3xl md:text-4xl lg:text-5xl text-white font-bold">{{ $course->title }}</h1>

                <div class="flex flex-col sm:flex-row sm:justify-between my-5 text-gray-500 text-base">
                    <div class="flex gap-4">
                        <small><i class="text-base uil uil-video"></i> {{ $videoCount }} videos</small>
                        <small><i class="text-base uil uil-clock"></i> {{ date('G\h i\m s\s', strtotime($course->duration)) }} duration</small>
                    </div>
                    <small>Created on {{ $course->created_at->format('d F Y') }}</small>
                </div>

                <div class="my-10" x-data="{expanded: false}">
                    <h1 class="text-white text-xl sm:text-2xl font-medium mb-3">Description</h1>
                    <p class="mb-2 font-normal text-xs sm:text-sm text-gray-400">
                        @if (strlen($course->description) > 300)
                            <span x-show="expanded">
                                {!! nl2br(e($course->description)) !!} 
                                <span class="text-indigo-400 hover:cursor-pointer" @click="expanded=false"> See less</span>
                            </span>
                            
                            <span x-show="!expanded">
                                {!! nl2br(e(substr($course->description, 0, 300))) !!}... 
                                <span class="text-indigo-400 hover:cursor-pointer" @click="expanded=true"> See more</span>
                            </span>
                        @else
                            <span> {!! nl2br(e($course->description)) !!} </span>
                        @endif
                    </p>
                </div>
                
                @if($course->author->id == auth()->user()->id || $hasBroughtCourse)
                    <div class="my-10">
                        <h1 class="text-white text-xl sm:text-2xl font-medium mb-3">Videos</h1>
                        <div class=" mx-auto rounded-xl border-2 border-gray-700 text-white" x-data="{selected:-1}">
                            <ul class="shadow-box">                  
                                @php $counter = 1; @endphp   
                                @foreach($course->courseItems as $key => $item) 
                                    <li class="relative ">
                                        <button type="button" class="w-full px-8 py-6 text-left" @click="selected !== {{ $key }} ? selected = {{ $key }} : selected = null">
                                            <div class="flex items-center justify-between">
                                                <h1 class="text-xl font-bold"><span class="mr-2">{{ $counter }}.</span> {{ $item->title }}</h1>
                                                <i class="uil uil-angle-down text-xl"></i>
                                            </div>
                                        </button>
                            
                                        <div class="relative overflow-hidden transition-all max-h-0 duration-700" style="" x-ref="container1" x-bind:style="selected == {{ $key }} ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : ''">
                                            <div class="p-6">
                                                <video controls class="w-full h-full">
                                                    <source src="{{ asset('storage/courses/videos/'.$item->video) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video> 
                                            </div>
                                        </div>
                                    </li>

                                    @php $counter++; @endphp
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-span-1">
                <x-card>
                    <div class="flex flex-col items-center py-3 px-6">
                        <small class="text-gray-500">Author</small>

                        <div class="my-4">
                            <img 
                                class="w-28 h-28 lg:w-36 lg:h-36 rounded-full" 
                                src="{{ isset($course->author->avatar) ? asset('storage/avatars/'.$course->author->avatar) : asset('assets/images/avatar-default.png') }}" 
                                alt="User avatar">
                        </div>

                        <div class="w-full flex flex-col items-center">
                            <p class="text-sm">{{ $course->author->username }}</p>
                            <div>
                                <small class="text-xs text-gray-500">{{ $course->author->credit }} Credit</small>
                            </div>
                        </div>
                    </div>
                </x-card>

                <div class="my-8">
                    <div class="flex justify-between items-end">
                        <small class="text-gray-500"><i class="text-base uil uil-users-alt"></i> {{ $buyerCount }} user(s)</small>
                        <h4 class="text-lg font-medium text-green-500 text-center"><span class="text-sm">Rp</span> {{ number_format($course->price, 2, ',', '.') }}</h4>
                    </div>

                    @if ($course->author->id == auth()->user()->id)
                        <button onclick="document.querySelector('#linkEdit').click()" class="px-4 bg-gray-800 dark:bg-transparent border-green-500 font-semibold text-xs text-white dark:text-green-500 uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-500 dark:hover:text-white focus:bg-green-600 dark:focus:bg-green-500 active:bg-gray-900 dark:active:bg-green-400 focus:outline-none focus:ring-1 focus:ring-green-400 dark:focus:ring-offset-green-900 transition ease-in-out duration-300 mt-3 py-3 w-full border-2 rounded-xl" type="button">
                            Edit Class
                        </button>
                        <a href="{{ route('class.edit', ['course' => $course]) }}" id="linkEdit" class="hidden"></a>

                        <x-danger-button wire:click="destroy" class="mb-3 mt-2 py-3 w-full border-2 rounded-md">Delete Class</x-danger-button>
                    @elseif ($hasBroughtCourse)
                        <h1 class="mt-5 text-white font-medium text-xl text-center">Congratulations, you have bought this class.</h1>
                    @else
                        <x-primary-button wire:click="buy" class="my-3 py-3 w-full rounded-md">
                            Buy
                        </x-primary-button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>