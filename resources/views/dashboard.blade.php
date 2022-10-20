@extends('layouts.master')
@section('title')
لوحة التحكم - برنامج الفواتير
@stop
@section('css')
<!--  Owl-carousel css-->
<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" />
<!-- Maps css -->
<link href="{{URL::asset('assets/plugins/jqvmap/jqvmap.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="left-content">
						<div>
						  <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">اهلا بك في برنامج الكترون لفواتير!</h2>
						  <p class="mg-b-0">برنامج خاص بحساب الفواتير و الحركات عليها.</p>
						</div>
					</div>
					<div class="main-dashboard-header-right">
						<div>
							<label class="tx-13">تقيم البرنامج</label>
							<div class="main-star">
								<i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star"></i>
							</div>
						</div>
                        @can('قائمة المستخدمين')
						<div>
							<label class="tx-13">العاملين بالبرنامج</label>
							<h5>{{App\Models\User::count()}}</h5>
						</div>
                        @endcan
                        @can('الاقسام')
						<div>
							<label class="tx-13">الاقسام بالبرنامج</label>
							<h5>{{App\Models\Section::count()}}</h5>
						</div>
                        @endcan
					</div>
				</div>
				<!-- /breadcrumb -->
@endsection
@section('content')
				<!-- row -->
				<div class="row row-sm">
                    @can('قائمة الفواتير')
					<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
						<div class="card overflow-hidden sales-card bg-primary-gradient">
							<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
								<div class="">
									<h6 class="mb-3 tx-12 text-white">إجمالي الفواتير</h6>
								</div>
								<div class="pb-0 mt-0">
									<div class="d-flex">
										<div class="">
											<h4 class="tx-20 font-weight-bold mb-1 text-white">
                                                {{number_format(App\Models\Invoices::sum('total'), 2)}}£
                                            </h4>
											<p class="mb-0 tx-12 text-white op-7">
                                                عدد الفواتير ({{App\Models\Invoices::count()}})
                                            </p>
										</div>
										<span class="float-right my-auto mr-auto">
											<i class="fas fa-arrow-circle-up text-white"></i>
											<span class="text-white op-7">100%</span>
										</span>
									</div>
								</div>
							</div>
                            @php
                                $latestsNot = App\Models\Invoices::orderBy('id' ,'desc')->limit(18)->get();
                            @endphp
							<span id="compositeline" class="pt-1">
                                @foreach ($latestsNot as $latest)
                                    {{$latest->total}},
                                @endforeach
                            </span>
						</div>
					</div>
                    @endcan
                    @can('الفواتير الغير مدفوعة')
					<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
						<div class="card overflow-hidden sales-card bg-danger-gradient">
							<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
								<div class="">
									<h6 class="mb-3 tx-12 text-white">الفواتير الغير مدفوعة</h6>
								</div>
								<div class="pb-0 mt-0">
									<div class="d-flex">
										<div class="">
											<h4 class="tx-20 font-weight-bold mb-1 text-white">
                                                {{number_format(App\Models\Invoices::where('value_status', 2)->sum('total'),2)}}£
                                            </h4>
											<p class="mb-0 tx-12 text-white op-7">
                                                عدد الفواتير ({{App\Models\Invoices::where('value_status', 2)->count()}})
                                            </p>
										</div>
										<span class="float-right my-auto mr-auto">
											<i class="fas fa-arrow-circle-down text-white"></i>
											<span class="text-white op-7">
                                                @php
                                                    $allNot = App\Models\Invoices::count();
                                                    $partNot = App\Models\Invoices::where('value_status', 2)->count();
                                                    $latestsNot = App\Models\Invoices::where('value_status', 2)->orderBy('id' ,'desc')->limit(18)->get();
                                                @endphp
                                                {{round($partNot / $allNot * 100,2)}}%
                                            </span>
										</span>
									</div>
								</div>
							</div>
							<span id="compositeline2" class="pt-1">
                                @foreach ($latestsNot as $latest)
                                    {{$latest->total}},
                                @endforeach
                            </span>
						</div>
					</div>
                    @endcan
                    @can('الفواتير المدفوعة')
					<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
						<div class="card overflow-hidden sales-card bg-success-gradient">
							<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
								<div class="">
									<h6 class="mb-3 tx-12 text-white">الفواتير المدفوعة</h6>
								</div>
								<div class="pb-0 mt-0">
									<div class="d-flex">
										<div class="">
											<h4 class="tx-20 font-weight-bold mb-1 text-white">
                                                {{number_format(App\Models\Invoices::where('value_status', 1)->sum('total'),2)}}£
                                            </h4>
											<p class="mb-0 tx-12 text-white op-7">
                                                عدد الفواتير ({{App\Models\Invoices::where('value_status', 1)->count()}})
                                            </p>
										</div>
										<span class="float-right my-auto mr-auto">
											<i class="fas fa-arrow-circle-up text-white"></i>
											<span class="text-white op-7">
                                                @php
                                                    $all = App\Models\Invoices::count();
                                                    $part = App\Models\Invoices::where('value_status', 1)->count();
                                                    $latestsNot = App\Models\Invoices::where('value_status', 1)->orderBy('id' ,'desc')->limit(18)->get();
                                                @endphp
                                                {{round($part / $all * 100)}}%
                                            </span>
										</span>
									</div>
								</div>
							</div>
							<span id="compositeline3" class="pt-1">
                                @foreach ($latestsNot as $latest)
                                    {{$latest->total}},
                                @endforeach
                            </span>
						</div>
					</div>
                    @endcan
                    @can('الفواتير المدفوعة جزئيا')
					<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
						<div class="card overflow-hidden sales-card bg-warning-gradient">
							<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
								<div class="">
									<h6 class="mb-3 tx-12 text-white">الفواتير المدفوعة جزئياً</h6>
								</div>
								<div class="pb-0 mt-0">
									<div class="d-flex">
										<div class="">
											<h4 class="tx-20 font-weight-bold mb-1 text-white">
                                                {{number_format(App\Models\Invoices::where('value_status', 3)->sum('total'),2)}}£
                                            </h4>
											<p class="mb-0 tx-12 text-white op-7">
                                                عدد الفواتير ({{App\Models\Invoices::where('value_status', 3)->count()}})
                                            </p>
										</div>
										<span class="float-right my-auto mr-auto">
											<i class="fas fa-arrow-circle-down text-white"></i>
											<span class="text-white op-7">
                                                @php
                                                    $all = App\Models\Invoices::count();
                                                    $part = App\Models\Invoices::where('value_status', 3)->count();
                                                    $latestsNot = App\Models\Invoices::where('value_status', 3)->orderBy('id' ,'desc')->limit(18)->get();
                                                @endphp
                                                {{round($part / $all * 100)}}%
                                            </span>
										</span>
									</div>
								</div>
							</div>
							<span id="compositeline4" class="pt-1">
                                @foreach ($latestsNot as $latest)
                                    {{$latest->total}},
                                @endforeach
                            </span>
						</div>
					</div>
                    @endcan
				</div>
				<!-- row closed -->

				<!-- row opened -->
                @can('الفواتير')

				<div class="row row-sm">
					<div class="col-md-12 col-lg-12 col-xl-7">
						<div class="card">
							<div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
								<div class="d-flex justify-content-between">
									<h4 class="card-title mb-0">النسبة الخاصة بالفواتير</h4>
									<i class="mdi mdi-dots-horizontal text-gray"></i>
								</div>
								<p class="tx-12 text-muted mb-0">حساب الفواتير عن طريق رسم بياني توضيحي عن طريق النسب الخاصه بالفواتير.</p>
							</div>
							<div class="card-body">
                                {!! $chartjs->render() !!}
							</div>
						</div>
					</div>

                    {{-- Card Two --}}
					<div class="col-lg-12 col-xl-5">
						<div class="card card-dashboard-map-one">
							<label class="main-content-label">احصائية خاصة لفواتير</label>
							<span class="d-block mg-b-20 text-muted tx-12">حساب الفواتير الاجمالية</span>
							<div class="">
								{!! $chartjs2->render() !!}
							</div>
						</div>
					</div>
				</div>
                @endcan
				<!-- row closed -->

				<!-- row opened -->
				<div class="row row-sm">
					<div class="col-xl-4 col-md-12 col-lg-12">
						<div class="card">
							<div class="card-header pb-1">
								<h3 class="card-title mb-2">المنتجات</h3>
								<p class="tx-12 mb-0 text-muted">المنتجات الخاصة بالمشروع.</p>
							</div>
							<div class="card-body p-0 customers mt-1">
								<div class="list-group list-lg-group list-group-flush">
									<div class="list-group-item list-group-item-action" href="#">
										<div class="media mt-0">
											<img class="avatar-lg rounded-circle ml-3 my-auto" src="{{URL::asset('assets/img/faces/3.jpg')}}" alt="Image description">
											<div class="media-body">
												<div class="d-flex align-items-center">
													<div class="mt-0">
														<h5 class="mb-1 tx-15">المنتج االاول</h5>
														<p class="mb-0 tx-13 text-muted"> ملاحظات <span class="text-success ml-2">القسم </span></p>
													</div>
													<span class="mr-auto wd-45p fs-16 mt-2">
														<div id="spark1" class="wd-100p"></div>
													</span>
												</div>
											</div>
										</div>
									</div>
                                    <div class="list-group-item list-group-item-action" href="#">
										<div class="media mt-0">
											<img class="avatar-lg rounded-circle ml-3 my-auto" src="{{URL::asset('assets/img/faces/3.jpg')}}" alt="Image description">
											<div class="media-body">
												<div class="d-flex align-items-center">
													<div class="mt-0">
														<h5 class="mb-1 tx-15">المنتج الثاني</h5>
														<p class="mb-0 tx-13 text-muted"> ملاحظات <span class="text-success ml-2">القسم </span></p>
													</div>
													<span class="mr-auto wd-45p fs-16 mt-2">
														<div id="spark1" class="wd-100p"></div>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-md-12 col-lg-6">
						<div class="card">
							<div class="card-header pb-1">
								<h3 class="card-title mb-2">الاقسام</h3>
								<p class="tx-12 mb-0 text-muted">الاقسام اللي ترتبط بالمنتجات.</p>
							</div>
							<div class="product-timeline card-body pt-2 mt-1">
								<ul class="timeline-1 mb-0">
									<li class="mt-0"> <i class="ti-pie-chart bg-primary-gradient text-white product-icon"></i> <span class="font-weight-semibold mb-4 tx-14 ">القسم الاول</span> <a href="#" class="float-left tx-11 text-muted">تاريخ الانشاء</a>
										<p class="mb-0 text-muted tx-12">الملاحظات</p>
									</li>
									<li class="mt-0"> <i class="ti-bar-chart-alt bg-success-gradient text-white product-icon"></i> <span class="font-weight-semibold mb-4 tx-14 ">القسم الثاني</span> <a href="#" class="float-left tx-11 text-muted">تاريخ الانشاء</a>
										<p class="mb-0 text-muted tx-12">الملاحظات</p>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<!-- row close -->

			</div>
		</div>
		<!-- Container closed -->
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<!-- Moment js -->
<script src="{{URL::asset('assets/plugins/raphael/raphael.min.js')}}"></script>
<!--Internal  Flot js-->
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.pie.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.resize.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.categories.js')}}"></script>
<script src="{{URL::asset('assets/js/dashboard.sampledata.js')}}"></script>
<script src="{{URL::asset('assets/js/chart.flot.sampledata.js')}}"></script>
<!--Internal Apexchart js-->
<script src="{{URL::asset('assets/js/apexcharts.js')}}"></script>
<!-- Internal Map -->
<script src="{{URL::asset('assets/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<script src="{{URL::asset('assets/js/modal-popup.js')}}"></script>
<!--Internal  index js -->
<script src="{{URL::asset('assets/js/index.js')}}"></script>
<script src="{{URL::asset('assets/js/jquery.vmap.sampledata.js')}}"></script>
@endsection
