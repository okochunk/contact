@section('internal_css_library')
    <link rel="stylesheet" href="{{ asset("/AdminLTE-2.3.11/plugins/select2/select2.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/AdminLTE-2.3.11/plugins/select2/select2-bootstrap.css") }}">
@endsection

@section('internal_js_library')
    <script src="{{ asset("/AdminLTE-2.3.11/plugins/select2/select2.min.js") }}"></script>
@endsection

<form role="form" method="{{ $method }}" action="{{ $action }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if ($mode == 'edit')
        {{ method_field('PUT') }}
    @endif
    <div class="box-body">
        <div class="col-md-12 form-group {{ $errors->has('group') ? 'has-error' : '' }}">
            <label for="">@lang('contact.group')</label>
            <select id="group" name="group" class="form-control">
                @foreach($groups as $key => $group)
                    <option value="{{$key}}" {{ ($key == $contact->group_id) ? 'selected' : '' }}> {{$group}}</option>
                @endforeach
            </select>

            @if ($errors->has('group'))
                <span class='help-block'> {{ $errors->first('group') }}</span>
            @endif
        </div>

        <div class="col-md-6 form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
            <label for="name" class="control-label">@lang('contact.name')</label>
            <input id="name" name="first_name" type="text" class="form-control" value="{{ (old('first_name')) ? old('first_name') : $contact->first_name }}">
            @if ($errors->has('first_name'))
                <span class='help-block'> {{ $errors->first('first_name') }}</span>
            @endif
        </div>

        <div class="col-md-6 form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
            <label for="name" class="control-label">@lang('contact.last_name')</label>
            <input id="name" name="last_name" type="text" class="form-control" value="{{ (old('last_name')) ? old('last_name') : $contact->last_name }}">
            @if ($errors->has('last_name'))
                <span class='help-block'> {{ $errors->first('last_name') }}</span>
            @endif
        </div>

        <div class="form-group col-md-4 {{ $errors->has('country') ? 'has-error' : '' }}">
            <label for="">@lang('contact.country')</label>
            <select id="country" name="country" class="form-control country-select2" >
                @if ($mode != 'edit')
                <option value="" selected disabled>Select</option>
                @endif

                @foreach($countries as $key => $country)
                    <option value="{{$key}}" {{ ($key == $contact->country) ? 'selected' : '' }}> {{$country}}</option>
                @endforeach
            </select>

            @if ($errors->has('country'))
                <span class='help-block'> {{ $errors->first('country') }}</span>
            @endif
        </div>

        <div class="form-group col-md-4 {{ $errors->has('state') ? 'has-error' : '' }}">
            <label for="title">@lang('contact.state')</label>
            <select name="state" id="state" class="form-control">
                @if ($mode == 'edit')
                    @foreach($states as $key => $state)
                        <option value="{{$key}}" {{ ($key == $contact->state) ? 'selected' : '' }}> {{$state}}</option>
                    @endforeach
                @endif
            </select>

            @if ($errors->has('state'))
                <span class='help-block'> {{ $errors->first('state') }}</span>
            @endif
        </div>

        <div class="form-group col-md-4 {{ $errors->has('city') ? 'has-error' : '' }}">
            <label for="title">@lang('contact.city')</label>
            <select name="city" id="city" class="form-control">
                @if ($mode == 'edit')
                    @foreach($cities as $key => $city)
                        <option value="{{$key}}" {{ ($key == $contact->city) ? 'selected' : '' }}> {{$city}}</option>
                    @endforeach
                @endif
            </select>

            @if ($errors->has('city'))
                <span class='help-block'> {{ $errors->first('city') }}</span>
            @endif
        </div>

        <div class="col-md-12 form-group {{ $errors->has('address') ? 'has-error' : '' }}">
            <label for="" class="control-label">@lang('contact.address')</label>
            <textarea rows="3" id="address" name="address" type="textarea" class="form-control">{{ (old('address')) ? old('address') : $contact->address }}</textarea>
            @if ($errors->has('address'))
                <span class='help-block'> {{ $errors->first('address') }}</span>
            @endif
        </div>

        <div class="col-md-4 form-group {{ $errors->has('zip') ? 'has-error' : '' }}">
            <label for="name" class="control-label">@lang('contact.zip')</label>
            <input id="zip" name="zip" type="text" class="form-control" value="{{ (old('zip')) ? old('zip') : $contact->zip }}">
            @if ($errors->has('zip'))
                <span class='help-block'> {{ $errors->first('zip') }}</span>
            @endif
        </div>

        <div class="col-md-4 form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="name" class="control-label">@lang('contact.email')</label>
            <input id="email" name="email" type="text" class="form-control" value="{{ (old('email')) ? old('email') : $contact->email }}">
            @if ($errors->has('email'))
                <span class='help-block'> {{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="col-md-4 form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
            <label for="name" class="control-label">@lang('contact.phone')</label>
            <input id="phone" name="phone" type="text" class="form-control" value="{{ (old('phone')) ? old('phone') : $contact->phone }}">
            @if ($errors->has('phone'))
                <span class='help-block'> {{ $errors->first('phone') }}</span>
            @endif
        </div>

        <div class="col-md-12 form-group {{ $errors->has('note') ? 'has-error' : '' }}">
            <label for="" class="control-label">@lang('contact.note')</label>
            <textarea rows="3" id="note" name="note" type="textarea" class="form-control">{{ (old('note')) ? old('note') : $contact->note }}</textarea>
            @if ($errors->has('note'))
                <span class='help-block'> {{ $errors->first('note') }}</span>
            @endif
        </div>

        <div class="form-group col-md-12 {{ $errors->has('avatar') ? 'has-error' : '' }}">
            <label for="">@lang('contact.avatar')</label>

            @if ($mode == 'edit')
                <img src="{{ $contact->gravatar }}" alt="profile Pic">
            @else
                <input type="file" name="avatar">
            @endif

            @if ($errors->has('avatar'))
                <span class='help-block'> {{ $errors->first('avatar') }}</span>
            @endif
        </div>

    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <button type="submit" class="btn btn-primary">@lang('common.submit')</button>
    </div>
</form>

@section('internal_js_script')
<script type="text/javascript">
    $('.country-select2').select2({
        theme:'bootstrap',
        delay: 10,
        minimumInputLength: 3
    });

    $('#country').change(function(){
        var countryID = $(this).val();
        if(countryID){
            $.ajax({
                type:"GET",
                url:"{{url('get-state-collection')}}?country_id="+countryID,
                success:function(res){
                    if(res){
                        $("#state").empty();
                        $("#state").append('<option>Select</option>');
                        $.each(res,function(key,value){
                            $("#state").append('<option value="'+key+'">'+value+'</option>');
                        });

                    }else{
                        $("#state").empty();
                    }
                }
            });
        }else{
            $("#state").empty();
            $("#city").empty();
        }
    });

    $('#state').on('change',function(){
        var stateID = $(this).val();
        if(stateID){
            $.ajax({
                type:"GET",
                url:"{{url('get-city-collection')}}?state_id="+stateID,
                success:function(res){
                    if(res){
                        $("#city").empty();
                        $.each(res,function(key,value){
                            $("#city").append('<option value="'+key+'">'+value+'</option>');
                        });

                    }else{
                        $("#city").empty();
                    }
                }
            });
        }else{
            $("#city").empty();
        }

    });
</script>
@endsection