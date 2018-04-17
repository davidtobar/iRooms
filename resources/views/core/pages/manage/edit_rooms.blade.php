<!-- Edit -->
<div class="modal modal-form fade" id="editRoom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-principal" role="document">
    <div class="modal-content">
      <div class="modal-header head-p">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>

      <div class="modal-body body-p">
        <form id="editRooms">
          <div class="m-form">
            <h3 class="title-f">Edit Room <span></span></h3>
            <div class="row">
              <div class="form-group col-md-12">             
                <label for="inputName">Room Name</label>
                <input id="room_name" class="form-control meet" name="room_name" required/>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-8" id="sec-room">             
                <label for="inputColor">Color</label>
                <input type='text' id="roomC" name="roomC" value='' required/>
              </div>
              <div class="form-group col-md-4">             
                <label for="inputColor">Capacity</label>
                <div class="input-group spinner">
                  <i class="mdi mdi-account"></i><input type="number" id="roomCapacity" name="roomCapacity" class="form-control meet" min="1" required>
                  <div class="input-group-btn-vertical">
                    <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                    <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">             
                <label for="inputColor">Opening Time</label>
                <select class="form-control sel-time" name="opening_time" id="opening_time" required>
                  @foreach($schedule as $sched)
                    <option>{{ $sched->hours }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-4">             
                <label for="inputColor">Closing Time</label>
                <select class="form-control sel-time" name="closing_time" id="closing_time" required>
                  @foreach($schedule as $sched)
                    <option>{{ $sched->hours }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group col-md-4">             
                <label for="inputColor">Status</label>
                <select class="form-control" name="status" id="status" required>
                  <option value="1">Active</option>
                  <option value="0">Disable</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">             
                <label for="inputName">Room Layout:</label>
                <div class="modal-rooms">
                  @foreach($layouts as $layout)
                  <div class="radio-inline sec-radio">
                    <label class="radio radio-inline">
                      <div class="room-img">
                        <img src="{{ asset('img/' . $layout->img) }}">
                      </div>
                      <input type="checkbox" name="roomlayout" value="{{ $layout->id }}" id="roomlayout"><span>{{ $layout->name }}</span>
                    </label>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <div class="row">
              <div class="col-md-6">
                <a href="#" class="btn btn-advanced btn-block" data-dismiss="modal">Cancel</a>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-success btn-block">Edit Room</button>
              </div>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>