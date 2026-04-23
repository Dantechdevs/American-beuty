@extends('layouts.admin')
@section('title','Managers')
@section('content')
@include('admin.users._header', ['title'=>'Managers','icon'=>'fa-user-tie','color'=>'var(--gold)','roleKey'=>'manager'])
@include('admin.users._stats', ['active'=>'manager'])
@include('admin.users._table', ['roleKey'=>'manager','roleLabel'=>'Manager'])
@endsection