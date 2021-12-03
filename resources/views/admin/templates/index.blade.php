@extends('admin.layouts.app')
@section('content')
    <div class="m-portlet m-portlet--mobile">
        <x-auth-session-status :status="session('status')" />
        <x-auth-validation-errors :errors="$errors"/>
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        {{$title??''}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{$addLink}}" class="btn btn-primary m-btn m-btn--pill m-btn--custom m-btn--icon m-btn--air">
												<span>
													<i class="la la-cart-plus"></i>
													<span>
														{{$addText??'Добавить элемент'}}
													</span>
												</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="data_list">
                <thead>
                <tr>
                    @foreach($columns as $column)
                        <th>
                            {{$column['name']??''}}
                        </th>
                    @endforeach

                </tr>
                </thead>
            </table>
        </div>
    </div>
    <script>
        let columns = {!! @json_encode($columns) !!}
    </script>
@endsection

