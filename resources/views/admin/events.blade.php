@extends('layouts.admin')
@section('search')


 							<li class="d-flex flex-column flex-md-row">
 								<label class="text-primary fw-bold mx-1 text-center" style="white-space: nowrap;">Search by:</label>
 								<select class="form-select mb-3 select" aria-label=".form-select-lg example" id="searchType" onchange="filterRole();">
								  <option selected>Title</option>
								  <option value="1">Location</option>
								  <option value="2">Date</option>
								  <option value="3">Status</option>
								</select>
								<select class="select form-select mb-3" id="status" disabled style="display: none"  >
								  <option data-searchType="Status" value="1">Upcoming</option>
								  <option data-searchType="Status" value="2">Cancelled</option>
								  <option data-searchType="Status" value="3">Past</option>
								</select>
						<span id="option-container" style="visibility: hidden; position:absolute;"></span>

                            <div class="container-fluid">
							    <form class="d-flex">
							      <input class="form-control" type="search" placeholder="Search..." aria-label="Search">
							      <button class="btn btn-success text-nowrap" type="submit"><i class="bi bi-search"></i> </button>
							    </form>
							  </div>


							  
						
                            
                        </li>


@endsection

@section('content')


<link rel="stylesheet" type="text/css" href="{{asset('admin/other/toastr.min.css')}}">

<h1 class="h3 mb-3"><strong>Events</strong> Management</h1>
<div class="row d-flex mt-3 justify-content-center">
	
	<div class="container">
		<div class="row align-items-start justify-content-center">
	
<a href="{{route('admin.event.addform')}}" class="btn btn-success col-2">
 Add new
</a>

</div>                		

	</div>

</div>
					

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
											<td class=" d-md-table-cell text-dark">{{ $event->short_desc }}</td>
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
												<button value="{{$event->id}}" type="button" class="btn deleteEvent" data-bs-toggle="modal" data-bs-target="#deleteEventModal"><i class="bx bxs-trash bx-md text-danger"></i>
												  
												</button>
			
											
											</td>
										</tr>
										@endforeach
										
										
									</tbody>
					</table>
                    	
                    </div>

			<div class="modal fade" id="deleteEventModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
							  <div class="modal-dialog">
							  	<form action="{{url('/events/delete')}}" method="POST">
							  		@csrf
							  		  <div class="modal-content">
							      <div class="modal-header">
							        <h5 class="modal-title text-danger" id="ModalLabel">Delete Event</h5>
							        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							      </div>
							      <div class="modal-body">
							        <input type="hidden" name="event_id" id="event_id" >
							        <p class="fw-bold">Do you really want to delete this event?</p>
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
							        <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Yes, delete</button>
							      </div>
							    </div>
							  	</form>
							  
							  </div>
							</div>

                  <div class="row">
                    	<div class="col-sm-6 mt-3 mb-lg-5">
                    <strong>{{ $events->links('pagination::bootstrap-5')}}</strong>
                    	</div>
                    	
 
				</div>

</div>
</div>
	
</div>



<script src="{{ asset('admin/other/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('admin/other/toastr.min.js') }}"></script>


<script >
	$(document).ready(function(){
		$(document).on('click','.deleteEvent',function(e) {
			e.preventDefault();
			var event_id = $(this).val();
			$('#event_id').val(event_id);
			$('#deleteEventModal').modal('show');
		});
	});
	
</script>

<script>

    function filterRole(){
      var searchType = $("#searchType").find('option:selected').text();
      if (searchType == 'Status') { // stores searchType
      $("#option-container").children().appendTo("#status"); // moves <option> contained in #option-container back to their <select>
      var toMove = $("#status").children("[data-searchType!='"+searchType+"']"); // selects role elements to move out
      toMove.appendTo("#option-container"); // moves role elements in #option-container
      $("#status").removeAttr("disabled"); // enables select
      document.getElementById("status").style.display = "block";
      }
      else{
      	document.getElementById("status").style.display = "none";

      }
};
</script>





@if(Session::has('message'))
 <script >
 	toastr.success("{!! Session::get('message') !!}");
 	
 </script>
	
	@endif



                    	     
 @endsection