@extends('backend.app')

@section('title', 'Product Similarity')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">All Product Similarity List</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable"
                                    class="table table-bordered table-striped align-middle dt-responsive nowrap"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="column-id">#</th>
                                            <th column-content>Similarity</th>
                                            <th column-content>Amazon Title</th>
                                            <th column-content>Marketplace</th>
                                            <th column-content>Amazon Price</th>
                                            <th column-content>1688 Title</th>
                                            <th column-content>1688 Min</th>
                                            <th column-content>Margin</th>
                                            <th column-content>Already?</th>
                                            <th class="column-status">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Dynamic Data --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            var table = $('#datatable').DataTable({
                responsive: true,
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"],
                ],
                processing: true,
                serverSide: true,
                pagingType: "full_numbers",
                ajax: {
                    url: "{{ route('product.matches.index') }}",
                    type: "GET",
                },
                dom: "<'row table-topbar'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>>" +
                    "<'row'<'col-12'tr>>" +
                    "<'row table-bottom'<'col-md-5 dataTables_left'i><'col-md-7'p>>",
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records...",
                    lengthMenu: "Show _MENU_ entries",
                    processing: `
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`,
                },
                autoWidth: false,
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '5%'
                    },
                    {
                        data: 'similarity',
                        name: 'similarity',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'amazon_title',
                        name: 'amazon_title',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'amazon_marketplace',
                        name: 'amazon_marketplace',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'amazon_price',
                        name: 'amazon_price',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'ali1688_title',
                        name: 'ali1688_title',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'ali1688_min',
                        name: 'ali1688_min',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'estimated_margin',
                        name: 'estimated_margin',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'already_on_amazon',
                        name: 'already_on_amazon',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: true,
                        searchable: true,
                        className: 'text-center',
                    },
                ],
                columnDefs: [],
            });
        });
    </script>
@endpush
