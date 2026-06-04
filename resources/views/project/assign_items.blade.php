@extends('layout.master')
@section('title', 'Assign Item')
@section('header-title', 'Assign Item')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <h2>Assign Items to Project: {{ $project->name }}</h2>

                        <form method="POST" action="{{ route('projects.assign-items.store', $project->id) }}">
                            @csrf

                            <table>
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Assign Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="item_ids[]" value="{{ $item->id }}">
                                                {{ $item->item }} ({{ $item->rate }})
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="quantities[]" placeholder="0.00">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <button type="submit">Assign Items</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
