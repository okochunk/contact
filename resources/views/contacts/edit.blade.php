@extends('layouts.auth.customer-master')

@section('content-title')
    {{ '' }}
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Contact</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @include('contacts.form', [
                'action' => action('ContactController@update', $contact->id),
                'method' => 'POST',
                'mode' => 'edit'
            ])
        </div>
    </div>
</div>

@endsection