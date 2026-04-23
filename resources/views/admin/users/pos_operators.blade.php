@extends('layouts.admin')
@section('title','POS Operators')
@section('content')
@include('admin.users._header', ['title'=>'POS Operators','icon'=>'fa-computer','color'=>'#2563eb','roleKey'=>'pos_operator'])
@include('admin.users._stats', ['active'=>'pos_operator'])
@include('admin.users._table', ['roleKey'=>'pos_operator','roleLabel'=>'POS Operator'])
@endsection