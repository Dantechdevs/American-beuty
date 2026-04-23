@extends('layouts.admin')
@section('title','Administrators')
@section('content')
@include('admin.users._header', ['title'=>'Administrators','icon'=>'fa-user-shield','color'=>'var(--purple)','roleKey'=>'admin'])
@include('admin.users._stats', ['active'=>'admin'])
@include('admin.users._table', ['roleKey'=>'admin','roleLabel'=>'Administrator'])
@endsection