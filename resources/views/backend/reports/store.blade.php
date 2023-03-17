@extends('layouts.admin')

@section('title', 'Add new report')

@section('styles')



    @livewireStyles
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                Submit a new report
            </h3>
            <div class="card-toolbar">
                <div class="example-tools justify-content-center">
                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        <form id="creation-form">
            <div class="card-body">
                <div class="form-group mb-8">
                    <div class="alert alert-custom alert-default" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                        <div class="alert-text">
                            After you submit this report, the report status will still pending even the supervisor
                            approve it.
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="status">
                        <option value="0" disabled>-- Select report Type --</option>
                        @foreach (App\Models\Report::TYPE as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-1">
                    <div class="card card-custom" id="kt_card_1">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">Report information</h3>
                            </div>
                            <div class="card-toolbar">
                                <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1"
                                   data-card-tool="toggle"
                                   data-toggle="tooltip" data-placement="top" title="Toggle Card">
                                    <i class="ki ki-arrow-down icon-nm"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1"
                                   data-card-tool="reload"
                                   data-toggle="tooltip" data-placement="top" title="Reload Card">
                                    <i class="ki ki-reload icon-nm"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary" data-card-tool="remove"
                                   data-toggle="tooltip" data-placement="top" title="Remove Card">
                                    <i class="ki ki-close icon-nm"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            Here represent the data
                        </div>
                    </div>
                </div>


                <div class="form-group mb-1">
                    <label for="region">Report Description</label>
                    <textarea class="form-control" id="region" rows="5"></textarea>
                </div>

                <div class="card-footer">
                    <button type="button" onclick="store()" class="btn btn-primary mr-2">Store</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
        </form>

        <!--end::Form-->
    </div>
@endsection

@section('scripts')
    <script>
        function store() {
            const formData = new FormData();
            formData.append('name', document.getElementById('name').value);
            formData.append('status', document.getElementById('status').value);
            formData.append('region', document.getElementById('region').value);
            formData.append('image', document.getElementById('image').files[0]);
            formData.append('center_id', document.getElementById('center_id').value);
            formData.append('keeper_id', document.getElementById('keeper_id').value);

            axios.post('/auto/reports', formData)
                .then(function (response) {
                    toastr.success(response.data.message);
                    document.getElementById('creation-form').reset();
                })
                .catch(function (error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>


    <script>
        // This card is lazy initialized using data-card="true" attribute. You can access to the card object as shown below and override its behavior
        var card = new KTCard('kt_card_1');

        // Toggle event handlers
        // card.on('beforeCollapse', function (card) {
        //     setTimeout(function () {
        //         toastr.info('Before collapse event fired!');
        //     }, 100);
        // });

        // card.on('afterCollapse', function (card) {
        //     setTimeout(function () {
        //         toastr.warning('Before collapse event fired!');
        //     }, 2000);
        // });

        // card.on('beforeExpand', function (card) {
        //     setTimeout(function () {
        //         toastr.info('Before expand event fired!');
        //     }, 100);
        // });

        // card.on('afterExpand', function (card) {
        //     setTimeout(function () {
        //         toastr.warning('After expand event fired!');
        //     }, 2000);
        // });

        // Remove event handlers
        card.on('beforeRemove', function (card) {
            toastr.info('This card will show the related information will send in your report!');

            return confirm('Are you sure to remove this card ?'); // remove card after user confirmation
        });

        // card.on('afterRemove', function (card) {
        //     setTimeout(function () {
        //         toastr.warning('After remove event fired!');
        //     }, 2000);
        // });

        // Reload event handlers
        card.on('reload', function (card) {
            toastr.info('This card will show the related information will send in your report!');

            KTApp.block(card.getSelf(), {
                overlayColor: '#ffffff',
                type: 'loader',
                state: 'primary',
                opacity: 0.3,
                size: 'lg'
            });

            // update the content here

            setTimeout(function () {
                KTApp.unblock(card.getSelf());
            }, 2000);
        });
    </script>

    @livewireScripts
@endsection
