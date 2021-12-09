<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List Notes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @foreach ($notes as $note)
                        <article class="m-4">
                            <p>{{ $note->content }}</p>

                            <form action="{{ route('notes.destroy', $note->id) }}" method="POST">
                                <a class="btn btn-blue" href="{{ route('notes.show', $note->id) }}">Show</a>
                                <a class="btn btn-blue" href="{{ route('notes.edit', $note->id) }}">Edit</a>

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-red">Delete</button>
                            </form>
                        </article>
                    @endforeach

                    {{ $notes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
