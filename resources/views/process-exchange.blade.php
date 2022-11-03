@extends('layouts.app')
@section('content')

    <div class="relative flex justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="pt-8 sm:justify-start sm:pt-0" style="display: flex; justify-content:center;">
                <h1 class="give__away">
                    {{ data_get($transaction, 'data.amount') }} {{ data_get($transaction, 'type') }}
                    обменять на
                    {{ data_get($transaction, 'data.amount') }} {{ data_get($transaction, 'data.asset') }}
                </h1>
            </div>
            <div class="pt-8 sm:justify-start sm:pt-0" style="display: flex; justify-content:center;">
                <h3>
                    <span
                        class="{{ data_get($transaction, 'status') === \App\Models\Transaction::STATUS_CREATE ? 'process__step__active' : 'process__step' }}">
                        Ввод данных <img src="{{ asset('exchange-pointer.webp') }}" alt="">
                    </span>
                    <span
                        class="{{ data_get($transaction, 'status') === \App\Models\Transaction::STATUS_IN_PROGRESS ? 'process__step__active' : 'process__step' }}">
                        Подтверждение обмена
                    </span>
                </h3>
            </div>
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                @if(data_get($transaction, 'status') === \App\Models\Transaction::STATUS_CREATE)
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div
                            class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l currencies__card">
                            <p style="font-size: 30px; font-weight: 100; color: #1a1a1a; opacity: 60%; margin: 10px">
                                Данные для обмена</p>
                            <div style="display: flex; justify-content:center;">
                                <h3>Заявка активна <span id="time"></span></h3>
                            </div>
                            <div class="">

                                <form action="{{ route('processSave', data_get($transaction, 'id')) }}" method="get">
                                    @csrf
                                    <label for="promo_code">
                                        <span style="float: left">Введите промокод</span>
                                        <br>
                                        <input type="text" name="promo_code" id="promo_code">
                                    </label>
                                    <br>
                                    @if(data_get($transaction, 'data.asset') === 'UZS')
                                        <label for="card-number">
                                            <span style="float: left">Номер карты</span>
                                            <br>
                                            <input type="text" name="card_number" id="card_number">
                                        </label>
                                        <br>
                                    @else
                                        <label for="address">
                                            <span style="float: left">Адрес(куда нужно отправить)</span>
                                            <br>
                                            <input type="text" name="address" id="address">
                                        </label>
                                        <br>
                                    @endif
                                    <label for="name">
                                        <span style="float: left">Имя</span>
                                        <br>
                                        <input type="text" name="name" id="name">
                                    </label>
                                    <br>
                                    <label for="surname">
                                        <span style="float: left">Фамилия</span>
                                        <br>
                                        <input type="text" name="surname" id="surname">
                                    </label>
                                    <br>
                                    <label for="email">
                                        <span style="float: left">Email</span>
                                        <br>
                                        <input type="email" name="email" id="email">
                                    </label>
                                    <input type="hidden" name="transaction" id="transaction"
                                           value="{{ data_get($transaction, 'uuid') }}">
                                    <div style="margin-top: 20px;">
                                        Нажимая кнопку «Обменять», я соглашаюсь <br>
                                        с <a href="#" style="color: #2abb7f">
                                            Публичным договором-офертой
                                        </a>
                                    </div>
                                    <div>
                                        <button type="submit"
                                                style="cursor: pointer; margin: 10px; color: #fff; background: #2abb7f; height: 50px; width: 150px;">
                                            Обменять
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div
                            class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l currencies__card">
                            <p style="font-size: 30px; font-weight: 100; color: #1a1a1a; opacity: 60%; margin: 10px">
                                Данные для обмена</p>
                            <div class="process__info__block">
                                Уважаемые пользователи, обмен происходит в течении от 5минут до 1часа.
                                В случае проблем с получением средств просим связаться со службой поддержки и отправить номер транзакции. Успешных Вам обменов.
                            </div>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-1">
                        <div
                            class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l currencies__card">
                            <p style="font-size: 30px; font-weight: 100; color: #1a1a1a; opacity: 60%; margin: 10px">
                                Номер транзакции #{{ data_get($transaction, 'id') }}.
                                Статус <span style="color: #2abb7f; font-weight: bold;" id="status">
                                    {{ data_get(\App\Models\Transaction::STATUSES, data_get($transaction, 'status'), 'Ошибка! Свяжитесь с оператором') }}
                                </span>
                            </p>
                            <div class="process__info__block">
                                Уважаемые пользователи, обмен происходит в течении от 5минут до 1часа.
                                В случае проблем с получением средств просим связаться со службой поддержки и отправить номер транзакции. Успешных Вам обменов.
                                <br>
                                <div style="display: flex; justify-content: center;">
                                    <button id="status-check" style="cursor: pointer; margin: 10px; color: #fff; background: #2abb7f; height: 50px; width: 150px;">
                                        Проверить статус платежа
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
