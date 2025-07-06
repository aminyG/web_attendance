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



	<!-- [ Main Content ] start -->
	@yield('content')
	@include('layouts.partials.ext-js')
	@yield('scripts')
</body>

</html>