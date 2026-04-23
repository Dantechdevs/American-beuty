@extends('layouts.admin')
@section('title','Delivery Personnel')
@section('content')
@include('admin.users._header', ['title'=>'Delivery Personnel','icon'=>'fa-motorcycle','color'=>'var(--tango)','roleKey'=>'delivery'])
@include('admin.users._stats', ['active'=>'delivery'])
@include('admin.users._table', ['roleKey'=>'delivery','roleLabel'=>'Delivery Person'])
@endsection