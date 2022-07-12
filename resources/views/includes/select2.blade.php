@push('css_vendor')
    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script')
    {{-- select2 --}}
    {{-- <script src="{{ asset('AdminLTE/plugins/select2/js/select2.full.min.js') }}"></script> --}}
    <script src="{{ asset('javascript/select2/select2.full.js') }}"></script>
    <script src="{{ asset('javascript/select2/select2.full.min.js') }}"></script>
@endpush
