@push('css_vendor')
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endpush

@push('script_vendor')
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
@endpush

@push('script')
    <script>
        $('.datepicker').datetimepicker({
            icons: {
                time: 'far fa-calender'
            },
            format: 'YYYY-MM-DD',
            locale: 'id',
        })

        $('.datetimepicker').datetimepicker({
            icons: {
                time: 'far fa-clock'
            },
            format: 'YYYY-MM-DD',
            locale: 'id',
        })
    </script>
@endpush
