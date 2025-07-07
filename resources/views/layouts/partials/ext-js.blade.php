{{-- Required Js --> --}}
<script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>
{{--
<script src="assets/js/plugins/bootstrap.min.js"></script> --}}
<script src="{{ asset('assets/js/ripple.js') }}"></script>
<script src="{{ asset('assets/js/pcoded.min.js')}}"></script>

<!-- Apex Chart -->
<script src="{{ asset('assets/js/plugins/apexcharts.min.js')}}"></script>


<!-- custom-chart js -->
<script src="{{ asset('assets/js/pages/dashboard-main.js')}}"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- <!-- Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script> --}}
<!-- Popper.js for Bootstrap 4 (FIXED) -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('assets/js/plugins/bootstrap.min.js')}}"></script>

<!-- Ripple JS (optional) -->
<script src="{{ asset('assets/js/ripple.js')}}"></script>

@stack('scripts')