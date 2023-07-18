<x-card>
    <div class="py-3 px-6">
        <div class="mb-7">
            <small class="text-gray-500">Description</small>

            <div class="mt-3">
                <p class="text-gray-300 text-sm">{!! nl2br(e($forum->description)) !!}</p>
            </div>
        </div>

        <div>
            <small class="text-gray-500">Created {{ date('M d, Y', strtotime($forum->created_at)) }}</small>
        </div>
    </div>
</x-card>
