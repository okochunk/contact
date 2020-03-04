@extends('layouts.auth.customer-master')

@section('content-title')
    @lang('group.header')
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">

                    <div class="box-tools">
                        <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                            <a href="{{ action('GroupController@create') }}" class="btn btn-block btn-success">@lang('common.create') +</a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody><tr>
                            <th>@lang('group.id')</th>
                            <th>@lang('group.name')</th>
                            <th>@lang('group.created_at')</th>
                            <th></th>
                        </tr>

                        @forelse($categories as $key => $category)
                        <tr>
                            <td>{{ (($categories->currentPage() - 1) * $categories->perPage()) + $key + 1 }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ dates_format($category->created_at, true) }}</td>
                            <td><a href="{{ action('GroupController@edit', $category->id) }}" class="btn btn-block btn-warning">Edit</a></td>
                            <td>
                                <form action="{{ action('GroupController@destroy', $category->id) }}" method="post">
                                    {{csrf_field()}}
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn btn-block btn-danger" onclick="return myFunction();">Delete</button>
                                </form>
                            </td>
                        </tr>

                        @empty
                            <tr>
                                <td colspan="3" class="text-center">@lang('common.empty')</td>
                            </tr>
                        @endforelse

                        </tbody></table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer clearfix">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        {{ $categories->appends(['status' => Request::get('status')])->links() }}
                    </ul>
                    <small class="text-muted">
                    {{ $categories->count() }} of {{ $categories->total() }} group
                    </small>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>

@endsection

@section('internal_js_script')
    <script type="text/javascript">
        function myFunction() {
            if(!confirm("Are You Sure to delete this"))
                event.preventDefault();
        }
    </script>
@endsection