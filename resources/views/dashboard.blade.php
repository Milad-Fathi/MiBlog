<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- {{ __("You're logged in!") }} -->

                    <br>
                    @auth
                        @if(auth()->user()->role === 'writer' or auth()->user()->role === 'admin') 
                            <!-- <div class="make_blog"><a href="{{ route('blog.create') }}">make blog</a></div> -->
                            <b><a href="{{ route('blog.create') }}">make blog</a></b>
                        @endif
                    @endauth
                    <br>
                    @auth
                        @if(auth()->user()->role === 'writer' or auth()->user()->role === 'admin' ) 
                            <b><a href="{{ route('writer.blogs') }}">my blogs</a></b> 
                        @endif
                    @endauth 
                    <br>
                    @auth
                        @if(auth()->user()->role === 'writer' or auth()->user()->role === 'user' ) 
                            <b><a href="{{ route('blog.recommendations') }}">recommendations</a></b> 
                        @endif
                    @endauth  
                    <br><br>
                    <!-- @auth -->
                        <!-- @if(auth()->user()->role === 'writer') -->
                            <!-- <div class="make_blog"><a href="{{ route('blog.create') }}">Make Blog</a></div> -->
                            <!-- <div class="view_my_blogs"><a href="{{ route('writer.blogs') }}">View My Blogs</a></div>  -->
                        <!-- @endif -->
                    <!-- @endauth -->
                    <b><a href="/">home</a></b>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
