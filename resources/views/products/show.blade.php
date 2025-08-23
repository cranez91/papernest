@extends('layouts.app')

@section('content')
    @include('layouts.header')
    
    <div class="container-fluid mx-auto p-6">
        <!-- Banner -->
        <div class="w-full h-60 mb-8 overflow-hidden relative">
            <img src="/images/papeleria-producto-banner.jpg"
                 alt="Banner producto" 
                 class="w-full h-full object-cover">
        </div>

        <product-detail data-product='@json($product)'>
        </product-detail>
    </div>

    @include('layouts.footer')
@endsection