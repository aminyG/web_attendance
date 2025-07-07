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

	@if (session()->has('impersonate') && !str_starts_with(request()->path(), 'superadmin'))
		<div class="alert alert-warning mt-3">
			Anda sedang masuk sebagai Admin: {{ auth()->user()->name }}
			<a href="{{ route('superadmin.impersonate.stop') }}" class="btn btn-outline-dark btn-sm">Kembali ke
				Superadmin</a>
		</div>
	@endif
	<!-- [ Main Content ] start -->
	@yield('content')
	@include('layouts.partials.ext-js')
	@yield('scripts')
</body>

</html>