@extends ('layout')

@section('content')
    <div class="row equal">
        <div class="col-md-3">
            <div class="panel panel-ukblue">
                <div class="panel-heading"><i class="glyphicon glyphicon-globe"></i> &thinsp; Map
                </div>
                <div class="panel-body text-center">
                    <a href="{{route("fte.map")}}">
                        {{ HTML::image('/images/world.png', 'world', array( 'width' => 80, 'height' => 80 )) }}
                    </a>
                    <br><br>
                    View map of all flights in progress.
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-ukblue">
                <div class="panel-heading"><i class="glyphicon glyphicon-plane"></i> &thinsp; Flight Training Exercises
                </div>
                <div class="panel-body">
                    <div class="">
                        Fancy something different? VATSIM UK is proud to announce the launch of Flight Training Exercises –
                        the new
                        way to learn and have fun!<br/><br/>
                        Choose one of 3 launch exercises and take flight discovering the South East of the UK
                        and much more. To get started click on one of the exercises below and follow the instructions
                        provided.<br/><br/>
                        If you have any questions please contact the Pilot Training Department via the Helpdesk
                        ({{ HTML::link('https://helpdesk.vatsim.uk/','click here',array("target"=>"_blank")) }}).
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-ukblue">
                <div class="panel-heading"><i class="glyphicon glyphicon-time"></i> &thinsp; Past Flights
                </div>
                <div class="panel-body text-center">
                    <a href="{{route("fte.history")}}">
                        {{ HTML::image('/images/history.png', 'history', array( 'width' => 80, 'height' => 80 )) }}
                    </a>
                    <br><br>
                    View flight history.
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($exercises as $exercise)
            <div class="col-md-{{ 12 / $exercises->count() }} hidden-xs">
                <div class="panel panel-ukblue">
                    <div class="panel-heading"><i class="glyphicon glyphicon-triangle-right"></i> &thinsp; {{ $exercise->name }}</div>
                    <div class="panel-body">
                        @if($exercise->image)
                            <img src="{{ $exercise->image->asset() }}" width="100%" height="50px" alt="{{ $exercise->name }}">
                        @endif
                        <p style="margin-top: 10px;">{{ $exercise->description }}</p>
                        <div class="text-right">
                            <a href="{{ route('fte.exercises', $exercise) }}" class="btn btn-primary">View Details &gt;&gt;</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop
