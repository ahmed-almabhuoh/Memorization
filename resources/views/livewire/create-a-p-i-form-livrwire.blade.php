<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <form id="creation-form">
        <div class="card-body">
            <div class="card card-custom" data-card="true" id="kt_card_1" style="">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Card Tools</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1"
                            data-card-tool="toggle" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Toggle Card">
                            <i class="ki ki-arrow-down icon-nm"></i>
                        </a>
                        <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1"
                            data-card-tool="reload" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Reload Card">
                            <i class="ki ki-reload icon-nm"></i>
                        </a>
                        <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary" data-card-tool="remove"
                            data-toggle="tooltip" data-placement="top" title="" data-original-title="Remove Card">
                            <i class="ki ki-close icon-nm"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body" kt-hidden-height="423" style="">
                    <p>Here we go, some fee step to start interact with ou system as a <strong>ligal entity</strong>.
                        Please, do not share any of these information with <strong>anyone</strong>, these inforamtion
                        are <strong>highly secured</strong> to
                        be comfitable with single system to apply <strong>system-to-system</strong> mechansim.
                        Also you must have to save these information, the secret key will not be shown agian, otherwise
                        you need
                        to <strong>change</strong> it.
                    </p>
                    <p>After you submit a new request with your filled information, <strong>all information in this page
                            will stored and be trusted</strong>.
                    </p>


                    <center>
                        <div class="form-group row">
                            <label class="col-1"><strong>UUID</strong></label>
                            <div class="col-5">
                                <p>{{ $uuid }}</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-1"><strong>API Key</strong></label>
                            <div class="col-5">
                                <p>{{ $key }}</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-1"><strong>API Secret</strong></label>
                            <div class="col-5">
                                <p>{{ $secret }}</p>
                            </div>
                        </div>
                    </center>
                </div>
            </div>


            <div class="form-group mb-8">
                <div class="alert alert-custom alert-default" role="alert">
                    <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                    <div class="alert-text">
                        After you add a new API Key you can interact directly with our system as system-to-system
                        mechanism
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label>System name<span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Enter sysmte name ..." id="name" />
            </div>

            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select class="form-control" id="status">
                    <option value="0">-- Select API status --</option>
                    @foreach (App\Models\APIKEY::STATUS as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Rate limit<span class="text-danger">*</span></label>
                <input type="number" class="form-control" placeholder="Enter api rate limit  ..." id="rate_limit"
                    value="0" />
                <span class="form-text text-muted">If you need to make it unlimited, let a Zero (0) value in this
                    field</span>
            </div>


            <div class="card-footer">
                <button type="button" onclick="store('{{ $uuid }}', '{{ $key }}', '{{ $secret }}')"
                    class="btn btn-primary mr-2">Store</button>
                <button type="reset" class="btn btn-secondary">Cancel</button>
            </div>
    </form>



    {{-- <div class="form-group">
        <label for="status">Status <span class="text-danger">*</span></label>
        <select class="form-control" id="status">
            <option value="0">-- Select branch status --</option>
            @foreach (App\Models\Branch::STATUS as $status)
                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div> --}}
</div>
