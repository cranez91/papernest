@extends('layouts.app')

@section('content')
    <main class="grid min-h-full place-items-center bg-white px-6 py-24 sm:py-32 lg:px-8">
        <div class="text-center">
            <p class="text-base font-semibold text-lime-600">
                404
            </p>
            <h1 class="mt-4 text-5xl font-semibold tracking-tight text-balance text-gray-900 sm:text-7xl">
                No Encontrada
            </h1>
            <p class="mt-6 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8">
                Lo sentimos, no pudimos encontrar la página que estás buscando.
            </p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                <a href="{{ route('home') }}"
                    class="rounded-md bg-lime-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Ir a inicio
                </a>
            </div>
        </div>
    </main>
@endsection