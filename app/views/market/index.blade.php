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
        <input type="text" class="my-input" id="inp-search" autocomplete="on" placeholder="What do you want to find today?" autofocus/>&nbsp;
        <button id="btn-search" class="my-btn">Find</button>
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
                <th>Item</th>
                <th>Price</th>
                <th>Store</th>
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
    {scan:'airsoftsverige_com',name:'AirsoftSverige'},
    {scan:'strikerairsoft_se',name:'Striker Airsoft'}
]
</script>
<script src="/js/market.js"></script>
@stop