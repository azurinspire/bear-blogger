<div class="border rounded p-6 mt-6">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="mr-2">Import from</div>
                <select class="form-select" wire:model="directory">
                    <option selected> - - select - - </option>
                    @foreach ($directories as $dir)
                        <option value="{{ $dir }}">{{ str_replace(config('bear-blogger.import-path') . '/', '', $dir) }}</option>
                    @endforeach
                </select>
                <div class="mx-2">directory</div>
                @if ($directory && count($files))
                    <div x-data="fileImport()">
                        <button @click="go" {{ $status === 'uploading' ? 'disabled' : '' }} class="flex items-center justify-center px-5 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-{{ $color ?? 'indigo' }}-600 hover:bg-{{ $color ?? 'indigo' }}-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                            <span x-show="!uploading" class="mr-2">GO!</span>
                            <i x-show="uploading" class="fas fa-spinner fa-spin mr-2"></i> ({{ count($photos) }}/{{ count($files)  }})
                        </button>
                    </div>
                @elseif ($directory)
                    <div class="border border-red-600 px-4 py-2 rounded text-red-800 bg-red-100">No Files!</div>
                @endif
            </div>
            <div class="flex items-center">
                <div class="mr-2">|</div>
                @if ($type === 'gallery')
                    <button wire:click="publish" class="mr-2 flex items-center justify-center px-5 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-{{ $color ?? 'indigo' }}-600 hover:bg-{{ $color ?? 'indigo' }}-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Publish
                    </button>
                @endif
                <a href="?hide=1" class="flex items-center justify-center px-5 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-{{ $color ?? 'indigo' }}-600 hover:bg-{{ $color ?? 'indigo' }}-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                    Preview
                </a>
            </div>
        </div>
        <script>
            let index = 0;
            function fileImport() {
                return {
                    uploading: false,
                    go() {
                        if (this.uploading) {
                            return;
                        }

                        this.uploading = true;
                        index = 0;

                        @this.set('status', 'uploading');
                        const files = @this.get('files');

                        importOne(files);
                    }
                }
            }

            function importOne(files) {
                const photos = @this.get('photos');

                if (photos.length === index && files[index]) {
                    // Previous photo is imported, do next one
                    @this.call('import', files[index]);
                    index++;
                }

                if (photos.length < files.length) {
                    setTimeout(function () {
                        importOne(files);
                    }, 1000);
                } else {
                    this.uploading = false;
                    @this.set('status', 'uploaded');
                }
            }
        </script>

        <div>
            @foreach ($photos as $photo)
                <div class="p-2 flex justify-around">
                    <div class="h-24 w-24 rounded shadow">
                        <img class="h-24 w-24" src="{{ $this->thumb($photo['id']) }}"/>
                    </div>
                    <div class="flex-1 mx-4 flex flex-col justify-between">
                        <input wire:model="names.id_{{ $photo['id'] }}" type="text" class="form-input" value="{{ $photo['name'] }}"/>
                        @if ($type === 'blog')
                            <label class="mb-4"><input wire:model="heroMediaId" value="{{ $photo['id'] }}" type="radio" class="form-radio"> Hero Image</label>
                        @endif
                    </div>
                    <div class="flex flex-col w-32">
                        @if ($status !== 'uploading')
                            <button wire:click="up({{ $photo['id'] }})" class="mb-2 flex items-center justify-center px-5 py-2 border border-transparent text-base leading-6 font-medium rounded-md border-{{ $color ?? 'indigo' }}-600 hover:bg-{{ $color ?? 'indigo' }}-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">Up</button>
                            <button wire:click="down({{ $photo['id'] }})" class="flex items-center justify-center px-5 py-2 border border-transparent text-base leading-6 font-medium rounded-md border-{{ $color ?? 'indigo' }}-600 hover:bg-{{ $color ?? 'indigo' }}-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">Down</button>
                        @endif
                    </div>
                </div>
                <div class="p-2 mb-2 border-b border-gray-200 flex justify-between">
                    <div class="flex-1 mr-4">
                        <textarea wire:model="descriptions.id_{{ $photo['id'] }}" rows="4" class="form-input w-full"></textarea>
                    </div>
                    <div class="flex flex-col w-32">
                        @if ($status !== 'uploading')
                            <button wire:click="save" class="mb-2 flex items-center justify-center px-5 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-{{ $color ?? 'indigo' }}-600 hover:bg-{{ $color ?? 'indigo' }}-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">Save</button>
                            <button wire:click="delete({{ $photo['id'] }})" class="flex items-center justify-center px-5 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">Delete</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        @push('scripts')
        <script type="text/javascript">
            window.livewire.on('alert', (message, icon) => {
                swal(message, {
                    icon,
                    buttons: false,
                    timer: 1500,
                });
            });
        </script>
        @endpush
</div>