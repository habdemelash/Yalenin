@extends('layouts.admin')


@section('content')
<meta charset="utf-8" name="csrf-token" content="{{csrf_token()}}">

<link rel="stylesheet" type="text/css" href="{{asset('admin/other/toastr.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<h1 class="h3 mb-3"><strong>Events</strong> Management</h1>
<div class="row d-flex mt-3 justify-content-center">
	
	<div class="container">
		<div class="row align-items-start justify-content-center">
	
<a href="{{route('admin.event.addform')}}" class="btn btn-success col-2" ">
 Add new
</a>



		
	
	
</div>                		

	</div>

</div>
					@if(session()->has('message'))
					<script>
						toastr.success("{{'Done'}}");
					</script>

					@endif

                    <div class="" style="overflow-x: auto;">
                    	<table class="table table-hover my-0 table-responsive" id="eventsTable">
									<thead>
										<tr >
											<th>Title</th>
										
											<th class=" d-xl-table-cell"> Date</th>
											<th class=" d-xl-table-cell"> Starting time</th>
											<th class=" d-xl-table-cell"> Ending time</th>
											<th class=" d-xl-table-cell"> Short Description</th>
											<th>Status</th>
											<th class=" d-md-table-cell">Needed Volunteers</th>
											<th class=" d-md-table-cell">Joined by</th>
											<th>Picture</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
										@foreach($events as $event)
								<?php $on = new Carbon\Carbon(new DateTime($event->due_date));
							$start = new Carbon\Carbon(new DateTime($event->start_time));
							$end = new Carbon\Carbon(new DateTime($event->end_time));
							$st = $start->format('g:i A');
							$en = $end->format('g:i A');
								$formatted_date = $on->toFormattedDateString(); ?>
										<tr id="eid{{$event->id}}">
											<?php $members = App\Http\Controllers\Site\Home::howManyJoined($event->id); ?>
											
											<td class="text-dark" style="font-style: initial;">{{ $event->title }}</td>
											<td class=" d-xl-table-cell text-info">{{ $formatted_date }}</td>
											<td class=" d-xl-table-cell text-primary">{{ $st }}</td>
											<td>{{ $en}}</td>
											<td class=" d-md-table-cell text-warning">{{ $event->short_desc }}</td>
											<td class="">

												@if($event->status == 'Upcoming')
												<span class="badge bg-primary">{{$event->status}}</span>
												@elseif($event->status == 'Cancelled')
												
												<span class="badge bg-danger">{{$event->status}}</span>
												@else
												<span class="badge bg-dark">{{$event->status}}</span>
												@endif
												</td>
											<td>{{$event->needed_vols}}</td>
											<td>@if($members>= $event->needed_vols)
												<span class="badge bg-danger">Full</span><br>
												@endif
												
											@if($members<1)
											<span class="text-danger">No one joined yet</span>

											@elseif($members == 1)
											<a href="{{url('dash/event/viewmembers',$event->id)}}" style="white-space: nowrap; font-weight: 700">
												{{$members}} volunteer </a>
											@else
											<a href="{{url('dash/event/viewmembers',$event->id)}}" style="white-space: nowrap; font-weight: 700">
												{{$members}} volunteers </a>


											@endif

											</td>
											<td><img src="{{ asset('uploads/event-pictures') }}/{{ $event->picture}}" class="rounded-circle rounded me-1" alt="No picture" style="height: 60px;width: 60px;" /></td>
											<td class="d-flex flex-row">
												<a href="{{url('dash/event/updateform',$event->id)}}" class="mx-1"><i class="bx bxs-edit bx-md"></i></a>
												<a href="javascript:void(0)" onclick="deleteEvent({{$event->id}})" class="mx-1"><i class="bx bxs-trash bx-md text-danger"></i></a>
			
											
											</td>
										</tr>
										@endforeach
										
										
									</tbody>
					</table>
                    	
                    </div>




                    <!-- Modal -->

                    <div class="row">
                    	<div class="col-sm-6 mt-3 mb-lg-5">
                    <strong>{{ $events->links('pagination::bootstrap-5')}}</strong>
                    	</div>
                    	
 @if(Session::has('message'))
 <script >
 	toastr.success("{!! Session::get('message') !!}");
 	
 </script>
	
	@endif
</div>
<script src="{{ asset('admin/other/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('admin/other/toastr.min.js') }}"></script>

<script >
	function deleteEvent(id){
		if(confirm("Do you really want to delete the event?")){
			$.ajax({
				url: '/events/delete/'+id,
				type: 'GET',
				data: {
					_token : $("input[name=_token]").val()
				},
				success:function(response)
				{
					$("#eid"+id).remove();
				}
			});
		}
	}
	
</script>


                    	     
 @endsection