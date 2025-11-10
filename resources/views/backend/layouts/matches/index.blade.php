@extends('backend.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-content wrapper">
        <div class="container-fluid">
            <div class="p-4">
                <h1 class="text-xl font-bold mb-4">Matches</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Similarity</th>
                            <th>Amazon Title</th>
                            <th>Marketplace</th>
                            <th>Amazon Price</th>
                            <th>1688 Title</th>
                            <th>1688 Min</th>
                            <th>Margin</th>
                            <th>Already?</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matches as $match)
                            <tr>
                                <td>{{ number_format($match->similarity, 3) }}</td>
                                <td>{{ $match->amazon?->title }}</td>
                                <td>{{ $match->amazon?->marketplace }}</td>
                                <td>{{ $match->amazon?->price }}</td>
                                <td>{{ $match->ali1688?->title }}</td>
                                <td>{{ $match->ali1688?->price_min }}</td>
                                <td>{{ $match->estimated_margin }}</td>
                                <td>{{ $match->already_on_amazon ? 'Yes' : 'No' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">No matches yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $matches->links() }}
            </div>
        </div>
    </div>
@endsection
