<div class="panel panel-ukblue">
    <div class="panel-heading">
        <i class="fa {{isset($icon) ? $icon : 'fa-list'}}"></i> {{isset($title) ? $title : 'Waiting Lists'}}
    </div>
    <div class="panel-body">
        <div class="row pl-4 pr-4">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th class="text-center">Waiting List</th>
                        <th class="text-center">Number on List</th>
                        <th class="text-center">Average wait time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($waitingLists as $waitingList)
                    <tr>
                        <td>{{$waitingList->name}}</td>
                        <td>
                            <!--Number of active members on the waiting list -->
                        </td>
                        <td>
                            <!--Average wait time -->
                        </td>
                    </tr>
                    @endforeach
                    @if(count($waitingLists) == 0)
                    <tr>
                        <td colspan="3">You are not eligible to join any waiting lists</td>
                    </tr>
                    @endempty
                </tbody>
            </table>
        </div>
    </div>
</div>
