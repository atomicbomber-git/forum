<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title> Thread Detail </title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>
    <body class="font-sans bg-white">
        <div class="container mx-auto my-5">
            <h1 class="text-black tracking-wide mb-3 uppercase"> James Forum </h1>
            <p class="text-grey-darkest"> Where the Truth Resides </p>
        
            <div class="shadow-md text-white bg-blue-darkest my-5">
                <div class="p-4 border-b border-black">
                    <h2> Thread {{ $thread->id }}: "{{ $thread->title }}" </h2>
                </div>

                <div class="p-4 bg-grey-lightest text-black">
                    <p class="my-3">
                        {{ $thread->content  }}
                    </p>
                </div>
            </div>

            <div class="shadow-md bg-grey-lightest mt-5">
                <div class="p-4 border-b border-black bg-green-darkest text-white">
                    <h4> Post A Comment </h4>
                </div>

                <div class="p-4">
                    <form method="POST" action="{{ route('comment.create', $thread) }}">
                        @csrf

                        <textarea name="content" class="w-full focus:shadow-md border border-black mb-2 p-3" name="" id="" cols="30" rows="10"></textarea>
                
                        <div class="text-right">
                            <button class="py-2 hover:bg-red px-4 bg-red-dark text-white">
                                POST
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="shadow-md bg-grey-lightest mt-5">
                <div class="p-4 border-b border-black bg-indigo-darkest text-white">
                    <h4> Comments </h4>
                </div>

                <div class="p-4">
                    @foreach ($thread->comments as $comment)
                        @include('comment.unit', ['thread' => $thread, 'comment' => $comment])
                    @endforeach
                </div>
            </div>
        </div>
    </body>
</html>