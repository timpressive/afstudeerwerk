@extends('layouts.app')

@section('title', $event->title)
@section('content')
	<div class="banner"><img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->title }}"></div>
	@if (Auth::check() && $event->isAdmin())
		<a href="{{ route('events.edit', ['id' => $event->id]) }}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> edit</a>
	@endif
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12 profile-wrapper">
				<h2 class="profile-title">{{ $event->title }}</h2>
				<div class="row">
					<div class="col-md-12">
						<p class="description">{!! $event->description !!}</p>
					</div>
				</div>
				<div class="row data">
					<div class="col-md-8 col-md-offset-2">
						<h4>Data</h4>
						<div class="row">
							<div class="col-md-2">
								<p><i class="fa fa-calendar fa-fw accent"></i></p>
							</div>
							<div class="col-md-10">
								<p>{{ date("F dS H:i", strtotime($event->starts_at)) }} - {{ date("F dS H:i", strtotime($event->ends_at)) }}</p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2"><i class="fa fa-map-marker fa-fw accent"></i></div>
							<div class="col-md-10"><p>{{ $event->street }} {{ $event->number }},<br>{{ $event->zip }} {{ $event->city }}</p></div>
						</div>
					</div>

				</div>
				@if (Auth::check() && !$event->full() && !$event->isAdmin())
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<button type="button" class="btn btn-primary full-width" data-toggle="modal" data-target="#enter-event">sign up your team</button>
						</div>
					</div>
					@include('modals.enter-event')
				@endif
				<div class="row divider">
					<div class="col-md-4 col-md-offset-4 teams">
						<h3 class="text-center">Participators</h3>
					@if (0 < count($event->participators()) && count($event->participators()) <= 4)
						@foreach($event->participators() as $team)
								<div class="col-md-3 {{ ($index == 0) ? 'col-md-offset-'.floor((12 - 3*count($team->members())) / 2) : '' }} blocklink team">
									<div class="profile-img"><img src="{{ $team->thumb or 'img/emblem.png' }}" alt="{{ $team->name }}" title="{{ $team->name }}"></div>
								</div>
							@endforeach
						@elseif(count($event->participators()) > 4)
						@for ($i = 0; $i < 4; $i++)
								<div class="col-md-3 blocklink team">
									<div class="profile-img"><img src="{{ $event->participators()[$i]->thumb or 'img/emblem.png' }}" alt="{{ $event->participators()[$i]->name }}" title="{{ $event->participators()[$i]->name }}"></div>
								</div>
							@endfor
								<button class="btn btn-load">see all</button>
						@else
							<div class="col-md-12">
								<p class="empty">No sign ups yet</p>
							</div>
						@endif
					</div>
				</div>
				<div class="row admin-controls">
					<div class="col-md-12">
						<a href="{{ route('events.leaderboard', ['id' => $event->id]) }}" class="btn btn-primary pull-right">leaderboard</a>
						@if (Auth::check() && $event->isAdmin())
							<a href="{{ route('events.manage', ['id' => $event->id]) }}" class="btn btn-primary">manage</a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script src="js/forms.js"></script>
	<script type="text/javascript">
		$(function() {
			$('#team').select2({
				placeholder: "Do something",
				allowClear: true
			});
		});
	</script>
@stop