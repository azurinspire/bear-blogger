@if ($edit)
    <livewire:bear-blogger::photo-import :model="$model" :type="$type" />
@else
    @if (config('app.env') === 'local')
        <div class="text-center mb-4 pb-4 border-b-2 border-gray-100">
            <a href="{{ request()->server('HTTP_REFERER') }}" class="text-{{ $color ?? 'indigo' }}-600">Back to Edit</a>
        </div>
    @endif
    <div class="image-grid mx-auto my-6">
        @foreach ($photos as $photo)
            <div style="width: 360px" class="grid-item">
                <a href="{{ route('photo', $photo) }}">
                    {{ $photo }}
                </a>
            </div>
        @endforeach
    </div>
@endif