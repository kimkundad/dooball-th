<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu" data-widget="tree">
			<li class="">
				<a href="{{ URL::to('admin/dashboard') }}">
					<i class="fa fa-dashboard"></i>
					<span>&nbsp;Dashboard</span>
				</a>
			</li>
			@if ($menus)
				@foreach ($menus as $par)
					<li class="treeview {{ $par['this_active'] }}">
						<?php
							$mainLink = '#';
							if (count($par['childs']) == 0) {
								if (trim($par['menu_url'])) {
									$mainLink = trim($par['menu_url']);
								}
							}
						?>
						<a href="{{ $mainLink }}">
							<i class="{{ $par['menu_icon'] }}"></i>
							<span>&nbsp;{{ $par['menu_name'] }}</span>
							@if ($par['childs'])
								<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
							@endif
						</a>
						<!-- start level 2 -->
						@if ($par['childs'])
							<ul class="treeview-menu">
								@foreach ($par['childs'] as $chi)
									<?php
										$three_arrw = (count($chi['three_childs']) != 0)? '<span class="fa arrow"></span>' : '';
										$httpHost = (Request::secure()) ? 'https://' . $_SERVER['HTTP_HOST'] : 'http://' . $_SERVER['HTTP_HOST'];
										$three_link = (count($chi['three_childs']) != 0)? '#' : $httpHost . $chi['menu_url'];
									?>
									<li class="{{$chi['second_active']}}">
										<a href="{{$three_link}}">
											<i class="{{ $chi['menu_icon'] }}"></i>&nbsp;{{$chi['menu_name']}}{{$three_arrw}}
										</a>
										<!-- start level 3 -->
										@if (count($chi['three_childs']) != 0)
											<ul class="nav nav-third-level">
											@foreach ($three_childs as $three)
												<?php
													$url = $_SERVER['REQUEST_URI'];
													$three_active = (in_array($url, $three[2])) ? 'class="active"' : '';
												?>
												<li {{$three_active}}>
													<a href="{{$three[1]}}">{{$three[0]}}</a>
												</li>
											@endforeach
											</ul>
										@endif
										<!-- end level 3 -->
									</li>
								@endforeach
							</ul>
						@endif
						<!-- end level 2 -->
					</li>
				@endforeach
			@endif
		</ul>
	</section>
</aside>
