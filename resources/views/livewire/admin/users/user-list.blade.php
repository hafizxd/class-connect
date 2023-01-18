<div class="py-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h1 class="text-white text-5xl font-bold mb-10 text-center">Admin Page</h1>

        <div class="my-5 overflow-x-auto flex justify-center">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden sm:rounded-lg">
                    <table class="min-w-full text-sm text-gray-400">
                        <thead class="bg-gray-800 text-xs uppercase font-medium">
                            <tr>
                                <th class="px-6 py-3 text-left tracking-wider">#</th>
                                <th scope="col" class="px-6 py-3 text-left tracking-wider">
                                    User
                                </th>
                                <th scope="col" class="px-6 py-3 text-left tracking-wider">
                                    Credit
                                </th>
                                <th scope="col" class="px-6 py-3 text-left tracking-wider">
                                    Role
                                </th>
                                <th scope="col" class="px-6 py-3 text-left tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800">
                            @php $counter = 1; @endphp
                            @foreach ($users as $user)
                                <tr class="bg-black bg-opacity-20">
                                    <td class="px-6 py-3">{{ $counter }}</td>
                                    <td class="flex items-center px-6 py-4 whitespace-nowrap">
                                        <img 
                                            class="w-10 h-10 rounded-full" 
                                            src="{{ isset($user->avatar) ? asset('storage/avatars/'.$user->avatar) : asset('assets/images/avatar-default.png') }}" 
                                            alt="User avatar"> 
                                            <span class="ml-2 font-medium">{{ $user->username }}</span>
                                        </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $user->credit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($user->role == 1)
                                            Regular user
                                        @elseif ($user->role == 2)
                                            Mentor
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($user->role == 1)
                                        <button wire:click="upgradeUser({{ $user->id }})" class="px-4 py-2 bg-gray-800 dark:bg-transparent border-green-500 font-semibold text-xs text-white dark:text-green-500 uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-500 dark:hover:text-white focus:bg-green-600 dark:focus:bg-green-500 active:bg-gray-900 dark:active:bg-green-400 focus:outline-none focus:ring-1 focus:ring-green-400 dark:focus:ring-offset-green-900 transition ease-in-out duration-300 border-2 rounded-md">
                                            Upgrade to Mentor
                                        </button>
                                        @else 
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @php $counter++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{ $users->links('pagination::tailwind') }}
    </div>
</div>
