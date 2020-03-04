@extends('layouts.auth.customer-master')

@section('content-title')
    @lang('contact.header')
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">

                    <div class="box-tools">
                        <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                            <a href="{{ action('ContactController@create') }}" class="btn btn-block btn-success">@lang('common.create') +</a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody><tr>
                            <th>@lang('contact.id')</th>
                            <th>@lang('contact.name')</th>
                            <th>@lang('contact.address')</th>
                            <th>@lang('contact.email')</th>
                            <th>@lang('contact.phone')</th>
                            <th>@lang('contact.note')</th>
                            <th>@lang('contact.created_at')</th>
                            <th></th>
                        </tr>

                        @forelse($contacts as $key => $contact)
                        <tr>
                            <td>{{ (($contacts->currentPage() - 1) * $contacts->perPage()) + $key + 1 }}</td>
                            <td>
                                <img src="{{ $contact->gravatar }}" alt="profile Pic">
                                <p>{{ $contact->first_name }}</p>
                            </td>
                            <td>{{ $contact->address }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ $contact->phone }}</td>
                            <td>{{ $contact->note }}</td>

                            <td>{{ dates_format($contact->created_at, true) }}</td>
                            <td><a href="{{ action('ContactController@edit', $contact->id) }}" class="btn btn-block btn-warning">Edit</a></td>
                            <td>
                                <form action="{{ action('ContactController@destroy', $contact->id) }}" method="post">
                                    {{csrf_field()}}
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn btn-block btn-danger" onclick="return myFunction();">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">@lang('common.empty')</td>
                             </tr>
                        @endforelse

                        </tbody></table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer clearfix">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        {{ $contacts->appends(['status' => Request::get('status')])->links() }}
                    </ul>
                    <small class="text-muted">
                    {{ $contacts->count() }} of {{ $contacts->total() }} contact
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