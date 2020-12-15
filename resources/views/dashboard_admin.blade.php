<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg text-center">
                <h1>Dashboard - Admin</h1>
                <div class="container col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 mx-auto">
                  <div class="row my-2">
                    <a class="btn btn-primary w-100" href="{{route('teachers.index')}}">Teachers</a>
                  </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
