@extends('layouts.app')
@section('content')
    <div class="flex">

        <div class="page-left-col" id="tq_statistic_block">

            <div class="index-main-block {{session('status')?'mt':''}}">
                @if(session('status'))
                    <div class="add-salary-finish">
                        <a class="close"></a>
                        {{session('status')}}
                    </div>
                @endif
                <div class="block-name">
                    {!! $settings->texts['main_form_title']?:'' !!}
                </div>
                    @if(isset($arStatistic['title']) && $arStatistic['title'])
                        <div class="top-text green">
                        {{$arStatistic['title']}}
                            @if(isset($arStatistic['title_error']) && $arStatistic['title_error'])
                                <span>{{$arStatistic['title_error']}}</span>
                            @endif
                            </div>

                    @else
                        <div class="top-text">
                        {!! $settings->texts['main_form_top_text']?:'' !!}
                        </div>
                    @endif

                <div class="price">

                    <span>{{__('wages.median_wage',[
    'rub'=>$arStatistic['title_medians']['rub']??0,
    'usd'=>$arStatistic['title_medians']['usd']??0,
    'eur'=>$arStatistic['title_medians']['eur']??0,
    ])}}</span>
                </div>

                    @if(!isset($arStatistic['error']))
                <div class="salary">
                    @if(isset($settings->main_form_salary) && $settings->main_form_salary)
                    <span>{{$settings->main_form_salary}}</span>
                    @endif
                        {{$settings->texts['main_form_salary_bottom']??''}}
                </div>
                    @endif
                    @if(!isset($userWage) || !$userWage)
                <button class="add-salary" onclick="location.href='{{route('salary.create')}}'">{{isset($userWage) && $userWage?__('wages.edit_btn'):__('wages.add_btn')}}
                </button>
                    @else
                        <div class="add-salary" style="width: 0; height: 0"></div>
                    @endif
                @if(isset($arStatistic) && $arStatistic)
                    @if(isset($arStatistic['error']) && $arStatistic['error'])
                            <div class="error-text">
                                @if(isset($settings->main_form_salary_error) && $settings->main_form_salary_error)
                                    <span>{{$settings->main_form_salary_error}}</span>
                                    {{$settings->texts['main_form_salary_bottom']??''}}
                                @endif
                                <p>{!! $arStatistic['error'] !!}</p>
                            </div>
                        @endif
                    @if(isset($arStatistic['percentiles']))
                    <div class="percentiles flex">
                        @foreach($arStatistic['percentiles'] as $key=> $percentile)
                            <div class="item" style="width: {{$percentile['width']}}%">
                                <p  data-title="{{__('wages.percentiles_hints.'.$key)}}">{{__('wages.percentile',['numb'=>$key])}} <br/>{{$percentile['value']}}</p>
                                @if($key == 25)
                                    <div class="center-arrow">
                                        <p data-title="{{__('wages.percentiles_hints.50')}}">{{$arStatistic['median']}}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                            <span class="tq_tooltip"></span>
                    </div>
                        @else
                            <div class="percentiles flex">
                                <div class="item">
                                </div>
                                <div class="item">
                                </div>
                                <div class="item">
                                </div>
                                <div class="item">
                                </div>
                                <div class="center-arrow">
                                </div>
                            </div>
                        @endif
                    @if(isset($arStatistic['steps']) && $arStatistic['steps'])
                        <div class="graph flex">
                            @foreach($arStatistic['steps'] as $step)
                                <div class="item">
							<div class="tq_line" style="height: {{$step['percent']}}%;">
								<div class="info">
									<p><span></span> {{$step['name']}}</p>
									<p>{{$step['value']}}</p>
								</div>
							</div>
                                    @if(isset($step['median']) && $step['median'] !== false )
                                        <div class="arrow">
                                            <p>{{$arStatistic['median']}}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="graph-values flex">
                            <div>{{$arStatistic['min']}}</div>
                            <div>{{$arStatistic['max']}}</div>
                        </div>
                        @else
                            <div class="graph small flex">
                                <div class="item">
                                    <span style="height: 15%;"></span>
                                </div>
                                <div class="item">
                                    <span style="height: 15%;"></span>
                                </div>
                                <div class="item">
                                    <span style="height: 15%;"></span>
                                </div>
                                <div class="item">
                                    <span style="height: 15%;"></span>
                                </div>
                                <div class="item">
                                    <span style="height: 15%;"></span>
                                </div>
                                <div class="item">
                                    <span style="height: 15%;"></span>
                                </div>
                                <div class="item">
                                    <span style="height: 15%;"></span>
                                </div>
                                <div class="item">
                                    <span style="height: 15%;"></span>
                                </div>
                                <div class="item">
                                    <span style="height: 15%;"></span>
                                </div>
                                <div class="item">
                                    <span style="height: 15%;"></span>
                                </div>
                            </div>
                    @endif

                @endif
                @if(Auth::check())
                        @if(isset($userWage) && $userWage)
                            <div class="salary-buttons">
                                <button onclick="location.href='{{route('salary.create')}}'">{{__('wages.edit_btn')}}</button>
                                <button onclick="location.href='{{route('salary.self')}}'">Смотреть зп по своей должности</button>
                                <button onclick="location.href='{{route('salary.index')}}'">Смотреть зп по фильтрам</button>
                                <span>{{__('wages.see_any_wages')}}</span>
                            </div>
                        @else
                    <div class="login-block flex">
                            <button @if(isset($userWage) && $userWage) onclick="location.href='{{route('salary.index')}}'" @else disabled @endif>{{__('wages.see_wages')}}</button>
                            <p>{{isset($userWage) && $userWage?__('wages.see_any_wages'):__('wages.add_wage_text')}}</p>
                    </div>
                        @endif
                @else
                    <div class="login-block flex">
                        <button onclick="location.href='{{route('login')}}'"><span></span> {{__('wages.to_auth')}}</button>
                        <p>{{__('wages.need_auth')}}</p>
                    </div>
                @endif

                <div class="paid-salaries">
                    {!! trans_choice('wages.form_quantity_bottom',$arStatistic['count']<5?5:$arStatistic['count'],['count'=>$arStatistic['count']<5?'<5':$arStatistic['count']]) !!}
                    @if($arStatistic['count']<5)
                        <br>
                        {{__('wages.not_enough')}}
                    @endif
                    <div class="link">
                    @if(isset($userWage) && $userWage)
                            <span class="thanks">{{__('wages.thx')}}</span>
                    @else
                        <a href="{{route('salary.create')}}">{{__('wages.add_href')}}</a>
                    @endif
                    </div>
                </div>
            </div>
            <x-commerce-block type="mob"/>
            <x-vacancy-block/>
        </div>
        <div class="page-right-col">
            <x-side-text/>
            <x-commerce-block/>
            <x-publication-block/>
        </div>
    </div>
    <script src="//js.pusher.com/7.0/pusher.min.js"></script>
    <script>

        var pusher = new Pusher('ef1671b9b4e3774c51f8', {
            cluster: 'eu'
        });
        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {

            $.ajax({
                url: '{{route(\Illuminate\Support\Facades\Route::current()->getName())}}',
                type: 'GET',
                //data: data,
                success: function (result) {
                    $('#tq_statistic_block').html($(result).find('#tq_statistic_block').html())
                }
            });
        });
    </script>
@endsection
