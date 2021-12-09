<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Note') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('notes.store') }}" method="POST">
                        @csrf

                        <div class="my-10">
                            <label for="content">Content:</label>
                            <textarea name="content" id="content" row="5" class="p-2 bg-gray-200 @error('content') is-invalid @enderror"></textarea>

                            @error('content')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-blue">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
