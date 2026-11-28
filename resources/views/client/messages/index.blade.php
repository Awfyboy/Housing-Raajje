@extends('client.layouts.master')

@section('title', 'Messages')
@section('activeNav', 'messages')

@section('content')
<div class="container-xxl px-3 px-lg-4 py-5">
    <div class="mb-4">
        <p class="eyebrow mb-2">Communication</p>
        <h1 class="section-heading mb-0">Messages</h1>
    </div>
    
    <div class="card p-5 text-center" style="background: var(--hr-surface); border-radius: 1rem; border: 1px solid var(--hr-border);">
        <h3 class="h5 fw-bold mb-2">(This is for the Messages page)</h3>
        <p class="text-muted-custom mb-0">Your secure messages with owners and staff will appear here.</p>
    </div>
</div>
@endsection