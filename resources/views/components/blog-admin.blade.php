@if (isset($post->id))
    <div class="flex mb-6">
        <form method="post" action="{{ route('bear-blogger.fetch', $post->slug) }}">
            @csrf
            <button class="mt-6 flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-{{ $color ?? 'indigo' }}-600 hover:bg-{{ $color ?? 'indigo' }}-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                Fetch
            </button>
        </form>
        @if (! $post->is_published)
            <form method="post" action="{{ route('bear-blogger.publish', $post->slug) }}">
                @csrf
                <button class="mt-6 ml-4 flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-{{ $color ?? 'indigo' }}-600 hover:bg-{{ $color ?? 'indigo' }}-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                    Publish
                </button>
            </form>
        @endif
        @if (session('status'))
            <div class="flex-1 mt-6 ml-4 px-4 py-3 border rounded {{ session('status') === 'success' ? 'border-green-600 bg-green-100 text-green-800' : 'border-red-600 bg-red-100 text-red-800' }}">
                {!! session('message') !!}
            </div>
        @endif
    </div>
@else
    <div class="flex">
        <form method="post" action="{{ route('bear-blogger.fetch.all') }}">
            @csrf
            <button class="mt-6 flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-{{ $color ?? 'indigo' }}-600 hover:bg-{{ $color ?? 'indigo' }}-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                Fetch New Posts
            </button>
        </form>
        @if (session('status'))
            <div class="flex-1 mt-6 ml-4 px-4 py-3 border rounded {{ session('status') === 'success' ? 'border-green-600 bg-green-100 text-green-800' : 'border-red-600 bg-red-100 text-red-800' }}">
                {{ session('message') }}
            </div>
        @endif
    </div>
@endif
