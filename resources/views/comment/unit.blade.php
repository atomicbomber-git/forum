<div class="shadow-outline my-2">
    <div class="border-black border-b p-2">
        <p class="font-bold text-grey-darkest text-xs mb-3">
            {{ $comment->poster->name }}:
        </p>
        <p>
            "{{ $comment->content }}"
        </p>
    </div>

    <div class="p-2 border-black border-b">
        <form method="POST" action="{{ route('comment.create', $thread) }}">
            @csrf

            <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">

            <textarea name="content" class="w-full focus:shadow-md border border-black mb-2 p-3" name="" id="" cols="30" rows="2"></textarea>

            <div class="text-right">
                <button class="py-1 hover:bg-red text-xs px-2 bg-red-dark text-white">
                    POST REPLY
                </button>
            </div>
        </form>
    </div>

    {{-- <div class="p2 ml-5 mr-5">
        @foreach ($comment->children as $child)
            @include('comment.unit', ['thread' => $thread, 'comment' => $child])
        @endforeach
    </div> --}}
</div>