@extends('layouts.site',['myevents'=>$myevents,'myEventsList'=>$myEventsList])

@section('content')
<style>
  #yours-tag{
    position: absolute; top: -95px; left: 40px; transform: rotate(-45deg);
     animation: left-right 2s ease-in-out infinite alternate-reverse both;



  }

  #days-left{
    position: absolute; top: -115px; right: 50px;
     



  }
  .event-image{
    height: 300px;

  }
  
</style>
<link rel="stylesheet" type="text/css" href="{{asset('admin/other/toastr.min.css')}}">
<section id="services" class="services section-bg mt-5">
      <div class="container">

        <div class="section-title">
          <span>Events</span>
          <h2>Events</h2>
          <p>Sit sint consectetur velit quisquam cupiditate impedit suscipit alias</p>
        </div>

        <div class="row d-flex justify-content-center">
          


           @php foreach($events as $event): @endphp
         <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-2" style="margin-right: 10px; margin-left: 10px; border-width: 5px;">
            <div class="icon-box row d-flex justify-content-center">
            	
        
           
              	<h4><?php echo($event->title); ?></h4>

              
              	<div class="icon" style="position: relative;">
                  <?php $days = App\Http\Controllers\Site\Home::calculateDays($event->id); ?>
              <span id="days-left" class="badge bg-success">{{$days}}</span>
                  @if(in_array($event->id, $myEventsList))
            <span id="yours-tag" class="badge bg-danger my-3">Your event</span>
             
            @endif
            <a href="{{url('events/view',$event->id)}}"><img class="img-fluid event-image" src="{{asset('uploads/event-pictures')}}/{{$event->picture}}"  style=""></i></a>
                 
          </div>
             <div class="row d-flex">
              <p class="text-primary"><?php echo($event->short_desc) ?></p>
              <p class="text-dark">{{(mb_substr($event->details,0,50,'UTF-8'))}} ...</p>         
             </div>
              <?php $members = App\Http\Controllers\Site\Home::howManyJoined($event->id); ?>            
              <hr>
              <div class="">
              	<p class="text-success"> We need <strong class="text-danger"><?php echo($event->needed_vols);?></strong> volunteers</p>


                
              	<p class="text-success"> <strong class="text-primary fw-bold"><?php echo($members);?></strong> volunteer(s) joined 
                @if($members>=$event->needed_vols)
                <span class="badge bg-danger">Full</span>
                @endif
                </p>
                
                <strong>Status: 
                  @if($event->status == 'Upcoming')
                  <span class="badge bg-info">{{$event->status}}</span> 
                  @elseif($event->status == 'Past')
                  <span class="badge bg-dark">{{$event->status}}</span> 
                  @else
                  <span class="badge bg-danger">{{$event->status}}</span> 
                  @endif
                </strong>
              	<div class="row d-flex flex-column justify-content-start">
                 <div class="col"><strong class="text-success"> <i class="bx bxs-calendar-event text-success">Date:</i><?php $c = new Carbon\Carbon( new DateTime($event->due_date));
                 $c2 = $c->toFormattedDateString();
                 echo($c2);?></strong></div>
                 <?php $on = new Carbon\Carbon(new DateTime($event->due_date));
              $start = new Carbon\Carbon(new DateTime($event->start_time));
              $end = new Carbon\Carbon(new DateTime($event->end_time));
              $st = $start->format('g:i A');
              $en = $end->format('g:i A');?>
                <div class="col"><strong><i class="bx bx-time text-danger" style="font-family: sans-serif;">Time:</i> <?php echo($st);?> - <?php echo($en);?></strong><br></div>
                <div class="col"><strong><i class="bx bx-current-location text-danger">Location</i><?php echo($event->location);?></strong> </div>
                </div>
              </div>
          
              @if(!in_array($event->id, $myEventsList))
              <div class="mt-3">
                @if($event->status == 'Upcoming' and $members<$event->needed_vols)
                <a href="{{url('join-event',$event->id)}}" class="fancy fw-bold" style="margin-top: 15px;" ><i class="bi-person-plus mx-1" style="font-size:20px;"></i> Join this event</a>
                @else
                <a class="fancy fw-bold fw-bold" href="" style="margin-top: 15px;pointer-events: none; background-color: red;">You can't join this</a>
                 <br>
                @endif
              </div>
              @else
              <div class="mt-3">
               
                 <a class="leave-btn fw-bold" href="{{url('leave-event',$event->id)}}" style="margin-top: 15px;"><i class="bi-x-lg mx-1" style="font-size:20px;"></i>Leave this event</a>
                 <br>

                 
              </div>

              @endif           
              
            </div>
          </div>
           

          @php
        endforeach;

           @endphp
<div class="text-center">
  {{$events->links('pagination::bootstrap-5')}}
</div>
 


        </div>

      </div>
    </section>
    <script src="{{ asset('admin/other/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('admin/other/toastr.min.js') }}"></script>
  @if(Session::has('message'))
  <script type="text/javascript">
    toastr.success("{{Session::get('message')}}");
  </script>
  @endif

    @endsection
