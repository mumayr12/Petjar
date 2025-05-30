<!-- Start Footer Area -->
<footer class="footer">
	<!-- Footer Top -->
	<div class="footer-top section">
		<div class="container">
			<div class="row">
				<div class="col-lg-5 col-md-6 col-12">
					<!-- Single Widget -->
					<div class="single-footer about">
						<div class="logo">
							<p>Petjar</p>
						</div>
						@php
							$setting = DB::table('settings')->first();
						@endphp
						<!-- Updated description text -->
						<p class="text">{{ 'An online pet shopping web App' }}</p>
						<p class="call">Got Question? Call us 24/7<span><a
									href="tel:{{ $setting->phone ?? '123456789' }}">{{ $setting->phone ?? '+1 234 5678' }}</a></span>
						</p>
					</div>
					<!-- End Single Widget -->
				</div>
				<div class="col-lg-2 col-md-6 col-12">
					<!-- Single Widget -->
					<div class="single-footer links">
						<h4>Information</h4>
						<ul>
							<li><a href="{{route('about-us')}}">About Us</a></li>
							<li><a href="#">Faq</a></li>
							<li><a href="#">Terms & Conditions</a></li>
							<li><a href="{{route('contact')}}">Contact Us</a></li>
							<li><a href="#">Help</a></li>
						</ul>
					</div>
					<!-- End Single Widget -->
				</div>
				<div class="col-lg-2 col-md-6 col-12">
					<!-- Single Widget -->
					<div class="single-footer links">
						<h4>Customer Service</h4>
						<ul>
							<li><a href="#">Payment Methods</a></li>
							<li><a href="#">Money-back</a></li>
							<li><a href="#">Returns</a></li>
							<li><a href="#">Shipping</a></li>
							<li><a href="#">Privacy Policy</a></li>
						</ul>
					</div>
					<!-- End Single Widget -->
				</div>
				<div class="col-lg-3 col-md-6 col-12">
					<!-- Single Widget -->
					<div class="single-footer social">
						<h4>Get In Touch</h4>
						<div class="contact">
							<ul>
								<li>{{ $setting->address ?? '123 Pet Street, Animal City' }}</li>
								<li>{{ $setting->email ?? 'info@petjar.com' }}</li>
								<li>{{ $setting->phone ?? '+1 234 5678' }}</li>
							</ul>
						</div>
					</div>
					<!-- End Single Widget -->
				</div>
			</div>
		</div>
	</div>
	<!-- End Footer Top -->
	<div class="copyright">
		<div class="container">
			<div class="inner">
				<div class="row">
					<div class="col-lg-6 col-12">
						<div class="left">
							<p>Copyright &copy; {{date('Y')}} <a href="#" target="_blank">Umayr</a> - All Rights
								Reserved.</p>
						</div>
					</div>
					<div class="col-lg-6 col-12">
						<div class="right">
							<img src="{{asset('backend/img/payments.png')}}" alt="Payment methods">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- /End Footer Area -->

<!-- Scripts -->
<script src="{{asset('frontend/js/jquery.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery-migrate-3.0.0.js')}}"></script>
<script src="{{asset('frontend/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('frontend/js/popper.min.js')}}"></script>
<script src="{{asset('frontend/js/bootstrap.min.js')}}"></script>
<script src="{{asset('frontend/js/slicknav.min.js')}}"></script>
<script src="{{asset('frontend/js/owl-carousel.js')}}"></script>
<script src="{{asset('frontend/js/magnific-popup.js')}}"></script>
<script src="{{asset('frontend/js/waypoints.min.js')}}"></script>
<script src="{{asset('frontend/js/finalcountdown.min.js')}}"></script>
<script src="{{asset('frontend/js/nicesellect.js')}}"></script>
<script src="{{asset('frontend/js/flex-slider.js')}}"></script>
<script src="{{asset('frontend/js/scrollup.js')}}"></script>
<script src="{{asset('frontend/js/onepage-nav.min.js')}}"></script>
<script src="{{asset('frontend/js/isotope/isotope.pkgd.min.js')}}"></script>
<script src="{{asset('frontend/js/active.js')}}"></script>

@stack('scripts')
<script>
	setTimeout(function () {
		$('.alert').slideUp();
	}, 5000);

	$(function () {
		// Multi Level dropdowns
		$("ul.dropdown-menu [data-toggle='dropdown']").on("click", function (event) {
			event.preventDefault();
			event.stopPropagation();

			$(this).siblings().toggleClass("show");

			if (!$(this).next().hasClass('show')) {
				$(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
			}

			$(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
				$('.dropdown-submenu .show').removeClass("show");
			});
		});
	});
</script>