<!DOCTYPE html>
<html lang="en">

@include('layouts.partials.style')

<body class="">
	<!-- [ Pre-loader ] start -->
	<div class="loader-bg">
		<div class="loader-track">
			<div class="loader-fill"></div>
		</div>
	</div>
	<!-- [ Pre-loader ] End -->
	<!-- [ navigation menu ] start -->
	@include('layouts.partials.navbar')
	<!-- [ navigation menu ] end -->
	<!-- [ Header ] start -->
	@include('layouts.partials.header')
	<!-- [ Header ] end -->


	@if (session()->has('impersonate'))
		<div class="alert alert-info">
			Anda sedang masuk sebagai Admin: {{ auth()->user()->name }}
			<a href="{{ route('superadmin.impersonate.stop') }}">Kembali ke Superadmin</a>
		</div>
	@endif

	<!-- [ Main Content ] start -->
	@yield('content')
	@include('layouts.partials.ext-js')
	@yield('scripts')
</body>

</html>