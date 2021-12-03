@extends('admin.layouts.app')
@section('content')
       <form action="{{route('admin.analytic-compare-filter')}}" method="post" class="m-form tq_analytic_form m-portlet m-portlet--tab" enctype="multipart/form-data">
        @csrf
           <x-auth-validation-errors :errors="$errors"/>
        @if(isset($filter) && $filter)
            <div class="m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
												<span class="m-portlet__head-icon m--hide">
													<i class="la la-gear"></i>
												</span>
                            <h3 class="m-portlet__head-text">
                                Параметры для сравнения 1
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
            @foreach($filter as $property)
                <div class="form-group m-form__group row">
                    <label for="first_filter_property_{{$property->id}}" class="col-2 col-form-label">
                        {{$property->name}}
                    </label>
                    <div class="col-10">
                        <select name="first_filter[properties][{{$property->name}}]" id="first_filter_property_{{$property->id}}">
                            <option></option>
                            @if(isset($property->options) && $property->options)
                                @foreach($property->options as $option)
                                    <option {{request(sprintf('first_filter.properties.%s',$property->name),old(sprintf('first_filter.properties.%s',$property->name))) == $option->value?'selected':''}}>{{$option->value}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            @endforeach
            </div>
            </div>
            <div class="m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
												<span class="m-portlet__head-icon m--hide">
													<i class="la la-gear"></i>
												</span>
                            <h3 class="m-portlet__head-text">
                                Параметры для сравнения 2
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
            @foreach($filter as $property)
                <div class="form-group m-form__group row">
                    <label for="property_{{$property->id}}" class="col-2 col-form-label">
                        {{$property->name}}
                    </label>
                    <div class="col-10">
                        <select name="second_filter[properties][{{$property->name}}]" id="property_{{$property->id}}">
                            <option></option>
                            @if(isset($property->options) && $property->options)
                                @foreach($property->options as $option)
                                    <option {{request(sprintf('second_filter.properties.%s',$property->name),old(sprintf('second_filter.properties.%s',$property->name))) == $option->value?'selected':''}}>{{$option->value}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            @endforeach
            </div>
            </div>

        @endif
           <div class="m-portlet--tab">
               <div class="m-portlet__head">
                   <div class="m-portlet__head-caption">
                       <div class="m-portlet__head-title">
												<span class="m-portlet__head-icon m--hide">
													<i class="la la-gear"></i>
												</span>
                           <h3 class="m-portlet__head-text">
                               Общие параметры
                           </h3>
                       </div>
                   </div>
               </div>
               <div class="m-portlet__body">
        <div class="form-group m-form__group row">
            <label for="date_from" class="col-2 col-form-label">
                Дата от
            </label>
            <div class="col-10">
                <input class="form-control m-input" name="date_from" id="date_from" type="datetime-local" max="9999-12-31T23:59" value="{{request('date_from',old('date_from'))}}">
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label for="date_to" class="col-2 col-form-label">
                Дата до
            </label>
            <div class="col-10">
               <input class="form-control m-input" name="date_to" type="datetime-local" max="9999-12-31T23:59" value="{{request('date_to',old('date_to'))}}">
            </div>
        </div>
        @if(isset($wageProperties) && $wageProperties)
                <div class="m-form__group form-group">
                    <label for="">
                        Сравнение по
                    </label>
                    <div class="m-radio-list">
                        @foreach($wageProperties as $property)
                            <label class="m-radio">
                                <input type="radio" name="wage" value="{{$property->id}}" {{request('wage',old('wage',$wage)) == $property->id?'checked':''}}>
                                {{$property->name}}
                                <span></span>
                            </label>
                        @endforeach
                    </div>
                </div>

        @endif
        <button class="btn btn-primary">Применить</button>
        <a href="{{route('admin.analytic-compare')}}" class="btn btn-secondary">Сбросить</a>
        </div>
        </div>
    </form>
    @if(isset($result) && $result)
                <div class="m-portlet m-portlet--tab">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    {{$result}}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
    @endif
    @if(isset($arStatistic) && $arStatistic)
        <div class="tq_blocks">
            @foreach($arStatistic as $key=> $statistic)

                <div class="m-portlet m-portlet--tab">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    {{$statistic['name']}} медиана {{number_format($statistic['median'],'0',',',' ')}}
                                    р, {{$statistic['count']}} – количество внесенных анкет, {{$statistic['percents']}}%
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    @endif

@endsection

