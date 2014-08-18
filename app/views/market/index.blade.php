@extends('layout')

@section('title')
| Market
@stop

@section('header')
{{ HTML::style('/css/market.css') }}
@stop

@section('content')
<div class="window-box-1">
    <div id="controls">
        <button id="btn-show-filters" class="my-btn">Filters</button>
        <input type="text" class="my-input" id="inp-search" autocomplete="on" placeholder="What do you want to find today?" autofocus/>&nbsp;
        <button id="btn-search" class="my-btn">Find</button>
        <div id="filter-area" class="hidden">
            <hr class="my-hr"/>
            <table>
                <tr>
                    <td>Stock only:</td>
                    <td><input type="checkbox" class="show-oos"/></td>
                </tr>
                <tr>
                    <td>Min. price:</td>
                    <td><input type="text" class="my-input min-price"/></td>
                </tr>
                <tr>
                    <td>Max. price:</td>
                    <td><input type="text" class="my-input max-price"/></td>
                </tr>
            </table>
            <hr class="my-hr"/>
            <div class="shops-list">
                @foreach($shops as $shop)
                @if ($shop->active)
                <label><input type="checkbox" id="sw-shop-{{ $shop->id }}" checked/>{{ $shop->name }}</label><br/>
                @endif
                @endforeach
            </div>
        </div>
    </div>
    <div id="item-viewer" class="hidden">
        <hr class="my-hr"/>
        <div class="controls">
            <button class="my-btn back">Back</button>
            <button class="my-btn open">Open original</button>
        </div>
        <hr class="my-hr"/>
        <div class="content"></div>
    </div>
    <div id="results">
        <table class="my-table">
            <thead>
            <tr>
                <th><a class="link" onclick="market.sortTable(this,0)">Item</a> <span></span></th>
                <th><a class="link" onclick="market.sortTable(this,1)">Price</a> <span></span></th>
                <th><a class="link" onclick="market.sortTable(this,2)">Store</a> <span></span></th>
                <th>Image</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
var stores =
[
    @foreach($shops as $shop)
    @if ($shop->active)
    {id:{{ $shop->id }},scan:'{{ $shop->plugin }}',name:'{{ $shop->name }}',rating:'{{ $shop->rating }}'},
    @endif
    @endforeach
]
var loggedIn = {{ Auth::check()*1 }}
</script>
<script src="/js/jquery.tinysort.min.js"></script>
<script src="/js/market.js"></script>
@stop