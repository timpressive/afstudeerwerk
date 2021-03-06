@extends('layouts.app')

@section('title', $user->username)
@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12">
				@if (Auth::check())
					@if ($user->id != Auth::user()->id)
						@if (!$user->isFriend(false))
							<a href="friends/{{$user->slug}}/add" class="btn btn-primary pull-right add-friend"><i class="fa fa-plus"></i> add friend</a>
						@elseif ($user->friendship()->confirmed)
							<form class="delete" data-confirm="unfriend {{ $user->username }}" action="friends/{{$user->friendship()->id}}/delete" method="POST">
								{{ csrf_field() }}
								<input type="hidden" name="_method" value="DELETE">
								<button class="btn btn-primary pull-right" type="submit"><i class="fa fa-user-times"></i> Unfriend</button>
							</form>
						@elseif (!$user->friendship()->confirmed)
							@if ($user->friendship()->user_id == Auth::user()->id)
								<form action="friends/{{$user->friendship()->id}}/delete" method="POST">
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="DELETE">
									<button type="submit" class="btn btn-primary pull-right">
										<i class="fa fa-ban"></i> Cancel request
									</button>
								</form>
							@else
								<a href="friends/{{$user->friendship()->id}}/confirm"
								   class="btn btn-primary pull-right">
									<i class="fa fa-check"></i> Confirm request
								</a>
							@endif
						@endif
					@elseif ($user->id == Auth::user()->id)
						<a href="{{ route('users.edit') }}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> edit profile</a>
					@endif
				@endif
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12 profile-wrapper">
				@if (Auth::check() && $user->id == Auth::user()->id)
					<i class="fa fa-2x fa-user menu-icons"></i>
				@endif
				<h1 class="profile-title">{{ $user->username }}</h1>
				<div class="row">
					<div class="col-md-12 text-center">
						<div class="profile-img profile">
							<img src="{{ $user->img or 'img/profile.png' }}" alt="{{ $user->username }}">
						</div>
						<p class="text-center">Joined {{ date('F jS Y', strtotime($user->created_at)) }}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-md-offset-4 text-center">
						<a href="{{ route('conversations.with' , ['id' => $user->id]) }}" class="message btn btn-primary">
							<i class="fa fa-comment"></i>Send message
						</a>
					</div>
				</div>
				<div class="row divider">
					<div class="col-md-12">
						<h3 class="text-center">Teams ({{count($user->teams())}})</h3>
						<div class="row">
							<div class="col-md-6 col-md-offset-3 {{ (count($user->teams(3)) > 0) ? 'teams' : '' }}">
								<div class="row {{ (count($user->teams(3)) > 0) ? 'blocklink-wrapper' : '' }}">
									@forelse ($user->teams(3) as $index => $team)
										<div class="col-md-4 {{ ($index == 0 && count($user->teams(3)) < 3) ? 'col-md-offset-'. 4 / count($user->teams(3)) : '' }} blocklink team">
											<a href="{{ route('teams.show', ['slug' => $team->slug]) }}">
												<div class="profile-img">
													<img src="{{ $team->emblem or 'img/emblem.png' }}" alt="{{ $team->name }}" title="{{ $team->name }}">
												</div>
											</a>
										</div>
									@empty
										<div class="col-md-12">
											<p class="text-center">{{ (Auth::check() && $user->id == Auth::user()->id) ? 'You are' : 'This user is' }} not in any teams yet.</p>
										</div>
									@endforelse
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-md-offset-3 {{ (count($user->friends(3)) > 0) ? 'friends' : '' }}">
						<h3 class="text-center">Friends ({{ count($user->friends()) }})</h3>
						<div class="row">
							<div class="row blocklink-wrapper">
								@forelse ($user->friends(3) as $index => $friend)
									<div class="col-md-4 {{ ($index == 0 && count($user->friends(3)) < 3) ? 'col-md-offset-'. 4 / count($user->friends(3)) : '' }} blocklink team">
										<a href="{{ route('users.show', ['slug' => $friend->slug]) }}">
											<div class="profile-img">
												<img src="{{ $friend->img or 'img/profile.png' }}" alt="{{ $friend->username }}" title="{{ $friend->username }}">
											</div>
										</a>
									</div>
								@empty
									<div class="col-md-12">
										<p class="text-center">{{ (Auth::check() && $user->id == Auth::user()->id) ? 'You have' : 'This user has' }} no friends yet.</p>
									</div>
								@endforelse
							</div>
							<div class="row">
								<div class="col-md-6 col-md-offset-3">
									<a href="{{ route('users.friends', ['slug' => $user->slug]) }}" class="btn btn-load full-width">
										See all friends
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop
@section('js')
	<script src="js/delete-confirm.js"></script>
	<script src="js/friend-requests.js"></script>
@stop
