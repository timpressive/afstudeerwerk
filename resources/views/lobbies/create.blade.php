@extends('layouts.app')

@section('title', 'New lobby')
@section('content')
    <h1>Create a lobby</h1>
    <form id="lobby-form" class="form-horizontal image-form" role="form" method="POST" action="{{ route('lobbies.store') }}" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Stealth mode</label>
                                    <p><em>Whether or not players in your lobby can see each other's username. +1 mystery!</em></p>
                                    <div class="row">
                                        <div class="col-md-8 col-md-offset-2">
                                            <div class="col-md-3">
                                                <input id="stealth_1" type="radio" name="stealth" value="1">
                                                <label for="stealth_1">On</label>
                                            </div>
                                            <div class="col-md-3 col-md-offset-6">
                                                <input id="stealth_0" type="radio" name="stealth" value="0" checked>
                                                <label for="stealth_0">Off</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="stealth-mode">
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-2">
                                        <label for="passphrase">Passphrase<i class="fa fa-asterisk"></i></label>
                                        <p><em>This is used by the players to identify one another (stealth mode)</em></p>
                                        <input type="text" autocomplete="off" name="passphrase" id="passphrase" class="form-control" placeholder="I hear Minsk is nice this time of year."  value="{{ old('passphrase') }}">

                                        @if ($errors->has('passphrase'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('passphrase') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-2">
                                        <label for="answer">Answer</label>
                                        <p><em>Optional answer to the passphrase (stealth mode)</em></p>
                                        <input type="text" name="answer" id="answer" class="form-control" placeholder="I've never been, the weather affects my knees" value="{{ old('answer') }}">

                                        @if ($errors->has('answer'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('answer') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-5">
                                    <label class="control-label" for="size">Lobby size<i class="fa fa-asterisk"></i></label>
                                    <p><em>Maximum amount of players</em></p>
                                    <input type="text" id="size" placeholder="0" name="size" class="form-control" value="{{ old('size') }}">

                                    @if ($errors->has('size'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('size') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-3 col-md-offset-4">
                                    <label class="control-label" for="meet_at">Meeting at<i class="fa fa-asterisk"></i></label>
                                    <p><em>When are you meeting?</em></p>
                                    <input type="time" id="meet_at" name="meet_at" class="form-control" value="{{ old('meet_at') }}">

                                    @if ($errors->has('size'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('meet_at') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group{{ $errors->has('location_name') ? ' has-error' : '' }}">
                                <div class="col-md-12">
                                    <label for="location_name" class="control-label">Location name<i class="fa fa-asterisk"></i></label><br>
                                    <p><em>The name of the game location (eg. local laser tag range)</em></p>
                                    <input id="location_name" type="text" class="form-control" name="location_name" value="{{ old('location_name') }}" placeholder="The local arena" autofocus>

                                    @if ($errors->has('location_name'))
                                        <span class="help-block">
                                <strong>{{ $errors->first('location_name') }}</strong>
                            </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <p><b>Location</b></p>
                                    <p><em>You can pass along the location's address for easy navigation</em></p>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <label for="street" class="control-label">Street<i class="fa fa-asterisk"></i></label><br>
                                            <input id="street" type="text" class="form-control" name="street" value="{{ old('street') }}" placeholder="Fake street" autofocus>

                                            @if ($errors->has('street'))
                                                <span class="help-block">
                                    <strong>{{ $errors->first('street') }}</strong>
                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <label for="number" class="control-label">Number<i class="fa fa-asterisk"></i></label><br>
                                            <input id="number" type="text" class="form-control" name="number" value="{{ old('number') }}" placeholder="123" autofocus>

                                            @if ($errors->has('number'))
                                                <span class="help-block">
                                    <strong>{{ $errors->first('number') }}</strong>
                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="zip" class="control-label">Zip code<i class="fa fa-asterisk"></i></label><br>
                                            <input id="zip" type="text" class="form-control" name="zip" value="{{ old('zip') }}" placeholder="1207" autofocus>

                                            @if ($errors->has('zip'))
                                                <span class="help-block">
                                <strong>{{ $errors->first('zip') }}</strong>
                            </span>
                                            @endif
                                        </div>
                                        <div class="col-md-8">
                                            <label for="city" class="control-label">City<i class="fa fa-asterisk"></i></label><br>
                                            <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" placeholder="Fakopolis" autofocus>

                                            @if ($errors->has('city'))
                                                <span class="help-block">
                                <strong>{{ $errors->first('city') }}</strong>
                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" id="description" placeholder="e.g. 'bring $5' or 'dress like James Bond'">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-2 col-md-offset-5">
                                    <input id="coords" type="hidden" name="coords">
                                    <button type="submit" class="btn btn-primary">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
@stop

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjI_a7-CJA5anDE0q3NSBHoccjlL31Dmk"></script>
    <script src="js/forms.js"></script>
    <script>
        $(function() {
            $('input[name="stealth"]').change(function() {
                if ($(this).val() == 1) { $('.stealth-mode').slideDown(); }
                else { $('.stealth-mode').slideUp(); }
            });
        });
    </script>
@stop
