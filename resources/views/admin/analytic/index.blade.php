@extends('admin.layouts.app')
@section('content')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        let items = {};
        document.addEventListener("DOMContentLoaded", function () {
            // Load the Visualization API and the corechart package.
            google.charts.load('current', {'packages': ['corechart']});

            // Set a callback to run when the Google Visualization API is loaded.
            google.charts.setOnLoadCallback(drawChart);

            // Callback that creates and populates a data table,
            // instantiates the pie chart, passes in the data and
            // draws it.
            function drawChart() {
                $.each(items, function (index, value) {
                    // Create the data table.
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Свойство');
                    data.addColumn('number', 'Значение');
                    data.addRows(value.items);

                    // Set chart options
                    var options = {
                        'title': value.name,
                        'width': 400,
                        'height': 300
                    };

                    // Instantiate and draw our chart, passing in some options.
                    var chart = new google.visualization.PieChart(document.getElementById(index));
                    chart.draw(data, options);
                })

            }
        });

    </script>
    <form action="{{route('admin.analytic-filter')}}" method="post" class="m-form tq_analytic_form m-portlet m-portlet--tab" enctype="multipart/form-data">
        <div class="m-portlet__body">
        @csrf
        @if(isset($filter) && $filter)
            @foreach($filter as $property)
                <div class="form-group m-form__group row">
                    <label for="property_{{$property->id}}" class="col-2 col-form-label">
                        {{$property->name}}
                    </label>
                    <div class="col-10">
                        <select name="properties[{{$property->name}}]" id="property_{{$property->id}}">
                            <option></option>
                            @if(isset($property->options) && $property->options)
                                @foreach($property->options as $option)
                                    <option {{isset($option->selected) && $option->selected?'selected':''}}>{{$option->value}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>


            @endforeach
        @endif
        <div class="form-group m-form__group row">
            <label for="date_from" class="col-2 col-form-label">
                Дата от
            </label>
            <div class="col-10">
                <input class="form-control m-input" name="date_from" id="date_from" type="datetime-local" max="9999-12-31T23:59" value="{{request('date_from')}}">
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label for="date_to" class="col-2 col-form-label">
                Дата до
            </label>
            <div class="col-10">
               <input class="form-control m-input" name="date_to" type="datetime-local" max="9999-12-31T23:59" value="{{request('date_to')}}">
            </div>
        </div>
        @if(isset($wageProperties) && $wageProperties)
                <div class="m-form__group form-group">
                    <label for="">
                        Аналитика по
                    </label>
                    <div class="m-radio-list">
                        @foreach($wageProperties as $property)
                            <label class="m-radio">
                                <input type="radio" name="wage" value="{{$property->id}}" {{request('wage',$wage) == $property->id?'checked':''}}>
                                {{$property->name}}
                                <span></span>
                            </label>
                        @endforeach
                    </div>
                </div>

        @endif
        <button class="btn btn-primary">Применить</button>
        <a href="{{route('admin.analytic')}}" class="btn btn-secondary">Сбросить</a>
        </div>
    </form>
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
                    <div class="m-portlet__body">
                        <div class="tq_charts">
                            @if(isset($statistic['items']) && $statistic['items'])
                                @foreach($statistic['items'] as $itemKey=> $item)
                                    <div class="tq_chart" id="chart_div_{{$key}}_{{$itemKey}}"></div>
                                @endforeach

                            @endif
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
        @if(isset($items) && $items)
            <script>
                items = @json($items)
            </script>
        @endif
    @else
        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                          Результатов не найдено
                        </h3>
                    </div>
                </div>
            </div>

        </div>
    @endif

@endsection

