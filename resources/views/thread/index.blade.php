<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title> All Threads </title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>
    <body class="font-sans bg-orange-dark">
        
        <div class="container mx-auto mt-5">
            <h1 class="tracking-wide my-5 uppercase">
                James Forum
            </h1>

            <div class="w-auto inline-block bg-grey-lightest p-4 shadow-md">
                <form method="POST" action="{{ route('thread.create') }}">
                    @csrf

                    <h2 class="mb-5"> Create New Thread </h2>
                    <br>

                    <label class="block tracking-medium mb-1"> Title: </label>
                    <input name="title" class="border border-black p-2 mb-3 w-full" type="text">

                    <label class="block tracking-medium mb-1"> Content: </label>
                    <textarea name="content" class="border border-black mb-3 p-2" name="" id="" cols="30" rows="10"></textarea>

                    <div class="text-right">
                        <button class="bg-red-dark hover:bg-red-light text-white px-3 py-1 uppercase font-bold tracking-wide">
                            Post
                        </button>
                    </div>
                </form>
                
            </div>

            @foreach ($threads as $thread)
            <div class="my-5">
                <div class="shadow-md bg-grey-lightest p-3 inline-block w-auto">
                    <span class="text-black font-bold text-xl mb-5 block">
                        {{ $thread->title  }}
                    </span>

                    <p class="text-grey-darkest mb-5">
                        {{ $thread->content  }}
                    </p>


                    <div class="text-right">
                        <a href="{{ route('thread.detail', $thread) }}" class="bg-blue-dark hover:bg-blue-light text-white px-3 py-1 uppercase font-bold tracking-wide">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
            
            @endforeach

        </div>
    </body>
</html>