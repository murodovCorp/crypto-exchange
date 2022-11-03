@extends('layouts.app')
@section('content')
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
        @if (Route::has('login'))
            <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                @auth
                    <a href="{{ url('/transactions') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                    @endif
                @endauth
            </div>
        @endif
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('process') }}" method="get">
                @csrf
                <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">

                    <h1 class="give__away">Отдаете</h1>
                    <label>
                        <input type="text" pattern="[0-9\.]*" decimal="true" inputmode="decimal" name="amount" value="1"
                               class="give__away__input" autofocus="" id="amount">
                    </label>
                    <label>
                        <select class="give__away__select" name="type" id="type">
                            @foreach($assets as $key => $asset)
                                <option value="{{ data_get($asset, 'asset_id') }}" @if($key === 0) selected @endif>
                                    {{ data_get($asset, 'name') }},{{ data_get($asset, 'asset_id') }}
                                </option>
                            @endforeach
                            <option value="UZS">
                                Узбекский сум
                            </option>
                        </select>
                    </label>
                </div>
                <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2" id="crypto-card" style="display: none">
                        @foreach($assets as $key => $asset)
                            @php
                                /** @var $key $key */
                                $isEven = ($key & 1) ? 'border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l' : '';
                            @endphp
                            <div class="p-6 {{ $isEven }} currencies__card">
                                <div class="flex items-center currencies__card__items">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                         stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500">
                                        <path
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <div class="ml-4 text-lg leading-7 font-semibold">
                                        <p>
                                            {{ data_get($asset, 'name', 'Не определено') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="ml-12">
                                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                <span class="currencies__card__items__get">
                                    Получите
                                </span>
                                        <br>
                                        <span class="currencies__card__items__price">
                                    {{ number_format((float)data_get($asset, 'price_usd', 0), 2) }}
                                </span>
                                        <br>
                                        <button type="submit" class="currencies__card__items__exchange" onclick="document.getElementById('asset').value = '{{ data_get($asset, 'asset_id', 0) }}'">
                                            Обменять <img src="{{ asset('arrow-right-green.webp') }}" alt="arrow" class="currencies__card__items__img">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="uzs-card">
                        <div class="p-6 currencies__card" style="display: block;">
                            <div class="flex items-center currencies__card__items">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                     stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500">
                                    <path
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <div class="ml-4 text-lg leading-7 font-semibold">
                                    <p>
                                        Узбекский сум
                                    </p>
                                </div>
                            </div>
                            <div class="">
                                <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                    <span class="currencies__card__items__get"> Получите </span>
                                    <br>
                                    <span class="currencies__card__items__price"> 123.000 </span>
                                    <br>
                                    <button type="submit" class="currencies__card__items__exchange">
                                        Обменять
                                        <img src="{{ asset('arrow-right-green.webp') }}" alt="arrow" class="currencies__card__items__img">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="asset" id="asset" value="UZS">
            </form>
        </div>
    </div>
@endsection
