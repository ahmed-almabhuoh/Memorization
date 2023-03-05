<form id="creation-form">
    <div class="card-body">
        <div class="form-group mb-8">
            <div class="alert alert-custom alert-default" role="alert">
                <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                <div class="alert-text">
                    After you add a new manager will take a super-manager role with all stored permissions in the
                    software system.
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>First name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Enter manager first name ..." id="fname" />
        </div>

        <div class="form-group">
            <label>Second name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Enter manager second name ..." id="sname" />
        </div>

        <div class="form-group">
            <label>Third name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Enter manager third name ..." id="tname" />
        </div>

        <div class="form-group">
            <label>Last name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Enter manager last name ..." id="lname" />
        </div>

        <div class="form-group">
            <label>Identity No. <span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Enter manager identity No. ..." id="identity_no" />
            <span class="form-text text-muted">We'll never share your identity No. with anyone else.</span>
        </div>

        <div class="form-group">
            <label>Phone No. <span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Enter manager phone No. ..." id="phone" />
            <span class="form-text text-muted">We'll never share your Phone No. with anyone else.</span>
        </div>

        <div class="form-group">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" placeholder="Enter manager email ..." id="email" />
            <span class="form-text text-muted">We'll never share your email with anyone else.</span>
        </div>

        <div class="form-group">
            <label for="gedner">Gender <span class="text-danger">*</span></label>
            <select class="form-control" id="gender">
                <option value="0">-- Select manager gender --</option>
                @foreach (App\Models\User::GENDER as $gender)
                    <option value="{{ $gender }}">{{ ucfirst($gender) }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status <span class="text-danger">*</span></label>
            <select class="form-control" id="status" wire:model="account_status">
                <option value="0">-- Select manager account status --</option>
                @foreach (App\Models\User::STATUS as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="password">Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="password" placeholder="Student password ..." />
        </div>

        <div class="form-group">
            <label>Photo</label>
            <div></div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="image" />
                <label class="custom-file-label" for="image">Choose manager photo</label>
            </div>
        </div>

        <div class="form-group mb-1">
            <label for="local_region">Local region</label>
            <textarea class="form-control" id="local_region" rows="3"></textarea>
        </div>

        <div class="form-group mb-1">
            <label for="description">Student description</label>
            <textarea class="form-control" id="description" rows="5"></textarea>
        </div>

        @if ($account_status === 'blocked')
            <div class="form-group mb-1">
                <label>Use date & time to block manager</label>
                <div class="col-9 col-form-label">
                    <div class="checkbox-inline">
                        <label class="checkbox checkbox-outline checkbox-success">
                            <input wire:model="user_date_and_time" type="checkbox" name="is_blocked"
                                checked="checked" id="is_blocked"/>
                            <span></span>
                            Date & Time
                        </label>
                    </div>
                    <span class="form-text text-muted">Some help text goes here</span>
                </div>
            </div>

            <div class="form-group mb-1">
                <label for="block_description">Block description</label>
                <textarea class="form-control" id="block_description" rows="5"></textarea>
            </div>

            @if ($user_date_and_time)
                <div class="form-group mb-1">
                    <label for="from_date">Block from</label>
                    <input class="form-control" type="datetime-local" value="2011-08-19T13:45:00" id="from_date" />
                </div>

                <div class="form-group mb-1">
                    <label for="to_date">Block to</label>
                    <input class="form-control" type="datetime-local" value="2011-08-19T13:45:00" id="to_date" />
                </div>
            @endif
        @endif
    </div>
    <div class="card-footer">
        <button type="button" onclick="store()" class="btn btn-primary mr-2">Store</button>
        <button type="reset" class="btn btn-secondary">Cancel</button>
    </div>
</form>
