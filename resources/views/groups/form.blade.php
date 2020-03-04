<form role="form" method="{{ $method }}" action="{{ $action }}">
    {{ csrf_field() }}
    @if ($mode == 'edit')
        {{ method_field('PUT') }}
    @endif
    <div class="box-body">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name" class="control-label">Group Name</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ (old('name')) ? old('name') : $group->name }}">
            @if ($errors->has('name'))
                <span class='help-block'> {{ $errors->first('name') }}</span>
            @endif
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <button type="submit" class="btn btn-primary">@lang('common.submit')</button>
    </div>
</form>