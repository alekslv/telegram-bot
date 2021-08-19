@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="text-center mb-2">
                            {{ __('You are logged in!') }}
                        </div>


                        <div class="alert alert-info">
                            <div>
                                Отправка на бот
                               @alekslvdel_bot
                            </div>
                        </div>

                        <div class="alert alert-info mb-2">
                            <p>Получить пользователей(cтавим на крон,лог users)</p>
                            <h2>
                                <a target="_blank" href="/updates">
                                    updates
                                </a>
                            </h2>
                        </div>
                        <div class="alert alert-info mb-2">
                            <div>
                                <h2>
                                    <a target="_blank" href="https://data.gov.ua/dataset/eda4e3cf-0dda-46a1-a78a-ee264ebbfe97">https://data.gov.ua/dataset/eda4e3cf-0dda-46a1-a78a-ee264ebbfe97</a>
                                </h2>
                              <br>
                                <a target="_blank" href="/scraper">Получить последний csv(ставить на крон,лог parser)</a>
                            </div>
                        </div>

                        <div class="alert alert-info mb-2">
                            <div>
                                <a target="_blank" href="/send_telegram">
                                    Отправить сообщения на  телегу(ставить на крон,лог send)
                                </a>
                            </div>
                        </div>

                        <div class="alert alert-info mb-2">
                            <div>
                                <a target="_blank" href="/logs">Лог действий</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
