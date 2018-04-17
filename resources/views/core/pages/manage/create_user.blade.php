<div class="modal modal-form fade" id="createUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-principal" role="document">
    <div class="modal-content">
      <div class="modal-header head-p">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body body-p">
        <form id="createUsers">
          <div class="m-form">
            <h3 class="title-f">Add User</h3>
            <div class="row">
              <div class="form-group col-md-12">             
                <label for="inputName">Full name</label>
                <input id="full_name" class="form-control meet" name="full_name" required/>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">         
                <label for="inputName">Username</label>
                <input id="username" class="form-control meet" name="username" required/>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">             
                <label for="inputPhone">Telephone</label>
                <input id="user_phone" class="form-control meet" name="user_phone" required/>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">             
                <label for="inputEmail">Email</label>
                <input type="email" id="user_email" class="form-control meet" name="user_email" required/>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">             
                <label for="inputRole">User Role</label>
                <select class="form-control sel-time" name="user_role" id="user_role" required>
                    <option value="0">User</option>
                    <option value="1">Manager</option>
                    <option value="2">Administrator</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">         
                <label for="inputPass">Password</label>
                <input type="password" id="user_password" class="form-control meet" name="user_password" pattern=".{6,}" required title="6 characters minimum"/>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">             
                <label for="inputStatus">Status</label>
                <select class="form-control" name="user_status" id="user_status" required>
                  <option value="1">Active</option>
                  <option value="0">Disable</option>
                </select>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <div class="row">
              <div class="col-md-6">
                <a href="#" class="btn btn-advanced btn-block" data-dismiss="modal">Cancel</a>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-success btn-block">Add User</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>