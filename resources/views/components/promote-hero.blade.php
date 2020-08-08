@if ($blogPost->hero_media_id === $mediaId)
    <div class="absolute p-4">Current Hero</div>
@else
    <form method="post" action="{{ route('bear-blogger.promote', [$blogPost->slug, $mediaId]) }}">
        @csrf
        <button class="opacity-50 hover:opacity-100 absolute flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-green-600 hover:bg-green-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
            Promote to Hero
        </button>
    </form>
@endif