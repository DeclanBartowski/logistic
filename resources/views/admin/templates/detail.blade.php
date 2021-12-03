@extends('admin.layouts.app')
@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        {{$item->name??'Добавление элемента'}}
                    </h3>
                </div>

            </div>
            @if(isset($delete,$back) && $delete && $back)

                <div class="m-portlet__head-tools">
                    <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm">
                        <li class="nav-item m-tabs__item">
                            <a class="btn btn-danger m-btn m-btn--icon" data-back="{{$back}}" data-ajax="true"
                               href="{{$delete}}">
                            <span>
                                <i class="la la-trash"></i>
                                <span>Удалить</span>
                            </span>
                            </a>

                        </li>
                    </ul>
                </div>
            @endif
        </div>
        <form action="{{$action}}" method="post" class="m-form" enctype="multipart/form-data">
            @if(isset($method) && $method)
                @method($method)
            @endif
            @csrf
            <x-auth-session-status :status="session('status')"/>
            <x-auth-validation-errors :errors="$errors"/>
            <div class="m-portlet__body">
                <ul class="nav nav-tabs" role="tablist">
                    @foreach($tabs as $key=> $tab)
                        <li class="nav-item">
                            <a class="nav-link {{$key == 'general'?'active show':''}}" data-toggle="tab"
                               href="#tab_{{$key}}">
                                {{$tab['name']??''}}
                            </a>
                        </li>
                    @endforeach

                </ul>
                <div class="tab-content">
                    @foreach($tabs as $key=> $tab)
                        <div class="tab-pane {{$key == 'general'?'active show':''}}" id="tab_{{$key}}" role="tabpanel">
                            @foreach($tab['fields'] as $field)
                                {!! $field !!}
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-3 ml-lg-auto">
                        @if(isset($send_mail) && $send_mail)
                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#send_feedback">
                                        Отправить сообщение
                                    </button>
                        @endif
                                </div>
                            <div class="col-lg-9 ml-lg-rigth" style="text-align: end;">
                                <button type="submit" class="btn btn-brand">
                                    Сохранить
                                </button>
                            </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

@endsection
@if(isset($send_mail))
    @if($send_mail)
        <div class="modal fade" id="send_feedback" tabindex="-1" role="dialog" aria-labelledby="send_feedback_label"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="send_feedback_label">
                            Отрпавить сообщение
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												&times;
											</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="send_feedback_form" action="{{route('send-feedback')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">
                                    E-Mail:
                                </label>
                                <input type="text" value="{{$item->user->email ?? ''}}"
                                       name="email"
                                       class="form-control"
                                       id="recipient-name">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">
                                    Заголовок:
                                </label>
                                <input type="text"
                                       name="title"
                                       class="form-control"
                                       id="recipient-name">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="form-control-label">
                                    Сообщение:
                                </label>
                                <textarea class="form-control" name="text" id="message-text"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Закрыть
                        </button>
                        <button type="submit" form="send_feedback_form" class="btn btn-primary">
                            Отрпавить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
