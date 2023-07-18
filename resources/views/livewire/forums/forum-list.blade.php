<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div x-data="{ openCreate: false }" x-cloak class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 grid-flow-col gap-10">
            <div class="col-span-1 hidden md:inline">
                <x-primary-button @click="openCreate = true" class="mb-4 w-full rounded-full py-4">
                    Create New Forum
                </x-primary-button>

                @livewire('side.top-user')
            </div>

            <div class="col-span-1 md:col-span-2 lg:col-span-3 mx-4 sm:mx-0">
                <x-primary-button @click="openCreate = true" class="md:hidden mb-3 w-full rounded-full py-3">
                    Create New Forum
                </x-primary-button>

                <div @card-closed.window="openCreate = $event.detail.openCreate" x-show="openCreate" class="mb-5" x-transition>
                    @livewire('forums.forum-create')
                </div>

                <div>
                    {{-- Filter --}}
                    <div class="flex justify-between">
                        <select wire:model="order" class="text-xs sm:text-sm rounded-full w-40 px-4 py-3 bg-slate-900 border-green-500 placeholder-gray-400 text-gray-300 focus:border-green-500 hover:cursor-pointer">
                            <option value="most-threads">Most Threads</option>
                            <option value="a_z">A - Z</option>
                            <option value="newest">Newest</option>
                        </select>

                        <input type="text" wire:model="search" placeholder="Search..." class="text-xs sm:text-sm w-44 sm:w-60 px-4 border-green-500 bg-slate-900 text-gray-300 focus:border-green-600 focus:ring-green-600 rounded-full">

                    </div>

                    {{-- List Thread --}}
                    <div class="my-5 grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach ($forums as $key => $forum)
                            <a href="{{ route('thread.index', ['forum' => $forum]) }}" class="h-full">
                                <div wire:key="{{ $key }}" class="flex relative mx-auto flex-col justify-between h-full w-full shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700 hover:dark:bg-slate-700 transition duration-300 ease-in-out">
                                    <div class="h-full overflow-hidden rounded-t-xl rounded-b-xl absolute z-0 w-full">
                                        <div class="bg-black opacity-75 absolute z-10 w-full h-full"></div>
                                        <img class=" w-full min-h-full" src="{{ asset('storage/forums/' . $forum->image) }}" alt="" />
                                    </div>
                                    <div class="p-5 flex flex-col justify-between grow z-10">
                                        <div class="mb-8">
                                            <h5 class="mb-5 font-medium text-base sm:text-2xl tracking-tight text-white">{{ $forum->name }}</h5>

                                            <p class="font-normal text-xs sm:text-sm text-gray-400">
                                                {!! nl2br(e(strlen($forum->description) > 200 ? substr($forum->description, 0, 150) . '...' : $forum->description)) !!}
                                            </p>
                                        </div>

                                        <div class="items-end">
                                            <div class="flex justify-between text-gray-400 text-sm">
                                                <small><i class="text-base uil uil-chat"></i> {{ $forum->threads()->count() }} Threads</small>

                                                @php
                                                    $userCount = 0;
                                                    foreach ($forum->threads as $thread) {
                                                        $userCount++;
                                                    
                                                        foreach ($thread->replies as $reply) {
                                                            $userCount++;
                                                        }
                                                    
                                                        foreach ($thread->nestedReplies as $nested) {
                                                            $userCount++;
                                                        }
                                                    }
                                                @endphp
                                                <small class="text-gray-400">{{ $userCount }} user(s) <i class="text-base uil uil-users-alt"></i></small>
                                            </div>

                                            <div class="flex flex-col text-sm">
                                                <h4 class="text-lg font-medium text-green-500 text-center"><span class="text-sm"><i class="uil uil-tag-alt"></i>
                                                        @php $categories = []; @endphp
                                                        @foreach ($forum->categories as $category)
                                                            @php $categories[] = $category->name; @endphp
                                                        @endforeach
                                                        {{ implode(', ', $categories) }}
                                                    </span></h4>
                                            </div>

                                            {{-- <x-primary-button class="w-full rounded-md dark:bg-transparent dark:border-green-500 dark:text-emerald-500 hover:dark:text-white py-3">Start Course</x-primary-button> --}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- <div class="my-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                        @foreach ($forums as $forum)
                            <div class="hover:cursor-pointer">
                                <a href="{{ route('thread.index', ['forum' => $forum]) }}">
                                    <x-card class="flex flex-col sm:flex-row sm:justify-between sm:gap-10 p-6">
                                        <div class="sm:hidden flex gap-4 items-center mb-3">
                                            <img class="w-10 h-10 rounded-lg" src="{{ asset('assets/images/avatar-default.png') }}" alt="User avatar">
                                        </div>

                                        <div class="flex flex-col justify-between">
                                            <div>
                                                <div class="sm:mb-3 font-medium text-base sm:text-lg">
                                                    <h3>{{ $forum->name }}</h3>
                                                </div>

                                                <div class="font-normal text-xs sm:text-sm text-gray-400">
                                                    <p class="break-words">{!! nl2br(e(strlen($forum->description) > 50 ? substr($forum->description, 0, 50) . '...' : $forum->description)) !!}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hidden sm:flex flex-col gap-4 items-center min-w-[20%] lg:min-w-[10%] pr-3">
                                            <img class="w-16 h-16 rounded-xl" src="{{ asset('assets/images/avatar-default.png') }}" alt="User avatar">
                                        </div>
                                    </x-card>
                                </a>
                            </div>
                        @endforeach
                    </div> --}}

                {{ $forums->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
</div>
