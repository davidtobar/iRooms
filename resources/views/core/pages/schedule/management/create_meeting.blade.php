<div class="modal modal-form fade" id="createMeeting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-principal" role="document">
    <div class="modal-content">
      <div class="modal-header head-p">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body body-p">
        <form id="createMeetings">
          <div class="m-form">
            <h3 class="title-f">Quick Book</h3>
            <h4 class="date-f quick-date" id="date-format"><?php echo date("l, F d Y"); ?></h4>
            <div class="row">
              <div class="form-group col-md-12">             
                <label for="inputName">Name</label>
                <input id="meeting_name" class="form-control meet" name="meeting_name" required/>
              </div>
            </div>
            <div class="row section-r">
              <div class="form-group col-md-12">
                <label for="inputRoom">Room</label>
                <select class="form-control meet" name="room" id="room" required>
                  <!-- <option selected="true" value="">Select Room</option> -->
                 @foreach($rooms as $room)
                   <option value="{{ $room->id }}"> {{ $room->name }} </option>
                 @endforeach
                </select>
                <select class="form-control lay meet" name="layouts" id="layouts"></select>
              </div>
            </div>

            <div class="row section-h">
              <div class="form-group col-md-12 section-danger">
                <div class="alert alert-danger">This room is fully occupied on this date. Please choose another room.
                </div>
              </div>
              <div class="form-group col-md-6 group-time">
                <label for="inputTime">From</label>
                <select class="form-control" name="start_time" id="start_time" required>
                </select>
              </div>
              <div class="form-group col-md-6 group-time">
                <label for="inputTime">To</label>
                <select class="form-control" name="end_time" id="end_time" required>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">             
                <label for="inputDescription">Description</label>
                <textarea class="form-control meet" rows="3" placeholder="Enter ..." name="description" required></textarea>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <div class="row">
              <div class="col-md-6">
                <a class="btn btn-advanced btn-block"><i class="mdi mdi-calendar-range"></i>Advanced</a>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-success btn-block"><i class="mdi mdi-plus"></i>Book</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal modal-form fade" id="listMeet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-principal" role="document">
    <div class="modal-content">
      <div class="modal-header head-p">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add New Cell Group</h4>
        <p class="totalModal">You have <span><b></b> meetings</span> this day</p>
      </div>
      <div class="modal-body body-p">
          <div class="row final-list"></div>
          <div class="modal-footer">
            <div class="row">
              <div class="col-md-6">
                <a href="{{route('manage-schedule-daily')}}" class="btn btn-advanced btn-block"><i class="mdi mdi-calendar-range"></i>Daily View</a>
              </div>
              <div class="col-md-6">
                <a href="{{route('manage-advanced-booking')}}" class="btn btn-success btn-block"><i class="mdi mdi-plus"></i>Book</a>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>