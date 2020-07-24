@if(count($notifications)>0)
@foreach($notifications as $notify)
<div class="post">
    <div class="user-block">
        <!--<img class="profile-user-img img-responsive img-circle" alt="User Image" src="/images/default.png">-->
        <span class="username">
            <a href="#">{{$notify->department}}</a>
            <label class="switch pull-right">
                <input type="checkbox" onchange="setActive('{{$notify->id}}')" @if($notify->is_active == 1) checked @endif>
                       <span class="slider round"></span>
            </label>    
        </span><br>
        <span class="description">Posted on - {{$notify->created_at}}</span><br>
        <span class="description">Posted by - {{$notify->idno}}</span>
    </div>
    <!-- /.user-block -->
    <p>
        {!!$notify->notification!!} 
    </p>
</div>
@endforeach
@endif
