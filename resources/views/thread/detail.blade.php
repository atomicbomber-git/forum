<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title> Thread Detail </title>

        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <script src="{{ asset('js/app.js') }}"></script>
    </head>
    <body class="font-sans bg-white">
        <div class="container mx-auto my-5">
            <a href="{{ route('thread.index') }}" class="text-black tracking-wide mb-3 uppercase">
                James Forum
            </a>
            <p class="text-grey-darkest"> Where the Truth Resides </p>
        
            <div class="shadow-md text-white bg-blue-darkest my-5">
                <div class="p-4 border-b border-black">
                    <h2 class="text-base">
                        <span class="uppercase"> Thread {{ $thread->id }}: </span>
                        "{{ $thread->title }}"
                    </h2>
                </div>

                <div class="p-4 bg-grey-lightest text-black">
                    <p class="my-3">
                        {{ $thread->content  }}
                    </p>
                </div>
            </div>

            <div class="shadow-md bg-grey-lightest mt-5">
                <div class="p-4 border-b border-black bg-green-darkest text-white">
                    <h2 class="text-base"> Post A Comment </h2>
                </div>

                <div class="p-4">
                    <form method="POST" action="{{ route('comment.create', $thread) }}">
                        @csrf

                        <textarea name="content" class="w-full focus:shadow-md border border-black mb-2 p-3" name="" id="" cols="30" rows="10"></textarea>
                
                        <div class="text-right">
                            <button class="py-2 hover:bg-red px-4 bg-red-dark text-white">
                                Post Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="shadow-md bg-grey-lightest mt-5">
                <div class="p-4 border-b border-black bg-indigo-darkest text-white">
                    <h4> Comments </h4>
                </div>

                <div class="p-4 bg-indigo-dark text-base">
                    @foreach ($comment_tree as $comment_branch)
                        @foreach ($comment_branch as $comment)
                            <div style="margin-left: {{ 2 * $comment->tree_depth }}rem" class="bg-white shadow-md my-2">
                                <div class="border-black border-b p-2">
                                    <p class="font-bold text-red text-xs mb-2">
                                        {{ $comment->poster_name }}:
                                    </p>
                                    <p>
                                        "{{ $comment->content }}"
                                    </p>

                                    <div class="text-right text-sm">

                                        <form method="POST" class="delete inline-block" action="{{ route('comment.delete', $comment->id) }}">
                                            @csrf
                                            <button class="px-3 py-2 text-grey-darkest">
                                                Delete
                                            </button>
                                        </form>
                                        
                                        <button data-comment-id="{{ $comment->id }}" class="reply px-3 py-2 text-white bg-yellow-darkest">
                                            Reply
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                    
                </div>
            </div>
        </div>
    </body>

    <div class="my-5">
        <div id="input-box" class="text-left">
            <label class="font-bold block my-3"> Write Your Reply Here: </label>
            <textarea id="input-textarea" class="block p-2 border border-blue w-full" rows="6"></textarea>
        </div>
    </div>

    <script>

        let input_original = document.getElementById('input-box');
        let input_box = input_original.cloneNode(true);
        input_original.parentNode.removeChild(input_original);

        $(input_box).find('#input-textarea').change(() => {
            // console.log($('#input-textarea').val());
            swal.setActionValue($('#input-textarea').val());
        });

        $(document).ready(function() {
            $('button.reply').each((i, elem) => {
                
                let comment_id = $(elem).data('comment-id');

                $(elem).click(() => {
                    swal({
                        title: 'Reply to Comment',
                        content: input_box,
                        buttons: {
                            cancel: true,
                            confirm: {
                                value: '',
                                closeModal: false
                            }
                        },
                        closeOnEsc: false,
                        closeOnClickOutside: false
                    })
                    .then(value => {
                        if (value == null) {
                            throw null;
                        }

                        return axios.post("{{ route('comment.create', $thread) }}", {
                            parent_comment_id: comment_id ,
                            content: value
                        });

                    })
                    .then(response => {
                        window.location.reload();
                    })
                    .catch(error => {

                    })
                });

            });

            $('form.delete').each((i, form) => {
                $(form).submit((e) => {
                    e.preventDefault();

                    swal({
                        title: 'Delete Confirmation',
                        text: 'Are you sure you want to delete this comment?',
                        buttons: {
                            cancel: true,
                            confirm: { closeModal: false }
                        },
                        icon: 'warning',
                        closeOnEsc: false,
                        closeOnClickOutside: false
                    })
                    .then(willDelete => {
                        if (willDelete == null) {
                            throw null;
                        }
                        
                        $(form).off('submit').submit();
                    })
                    .catch(error => {

                    })
                });
            });
        });
    </script>
</html>

{{-- <div class="p-2 border-black border-b"> --}}
    {{-- <form method="post" action="{{ route('comment.create', $thread) }}">
        @csrf

        <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">

        <textarea name="content" class="w-full focus:shadow-md border border-black mb-2 p-3" name="" id="" cols="30" rows="2"></textarea>

        <div class="text-right">
            <button class="py-1 hover:bg-red text-xs px-2 bg-red-dark text-white">
                REPLY
            </button>
        </div>
    </form> --}}

    {{-- <div class="text-right">
        <form action="{{ route('comment.delete', $comment->id) }}" method="post">
            @csrf
            <button class="bg-black text-white py-1 px-2 text-xs">
                Delete
            </button>
        </form>
    </div> --}}
{{-- </div> --}}