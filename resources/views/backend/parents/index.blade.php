@extends('layouts.admin')

@section('title', 'All Student Parents')

@section('styles')
    <style>
        #student_parent-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 1px solid rgb(44, 192, 214);
            padding: 3px;
        }
    </style>
    @livewireStyles
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">All Student Parents
                    <span class="text-muted pt-2 font-size-sm d-block">This table shows all student_parents stored in the
                        system</span>
                </h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Dropdown-->
                <div class="dropdown dropdown-inline mr-2">
                    <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="svg-icon svg-icon-md">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path
                                        d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                        fill="#000000" opacity="0.3"></path>
                                    <path
                                        d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                        fill="#000000"></path>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>Settings</button>
                    <!--begin::Dropdown Menu-->
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Navigation-->
                        <ul class="navi flex-column navi-hover py-2">
                            <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Choose
                                an option:</li>
                            <li class="navi-item">
                                <a href="#" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="la la-print"></i>
                                    </span>
                                    <span class="navi-text">Print</span>
                                </a>
                            </li>

                            <li class="navi-item" wire:model="only_trashed">
                                <a href="javascript:void(0)" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="la la-copy"></i>
                                    </span>
                                    <span class="navi-text">Only Trashed</span>
                                </a>
                            </li>

                            <li class="navi-item">
                                <a href="#" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="la la-copy"></i>
                                    </span>
                                    <span class="navi-text">Not Trashed</span>
                                </a>
                            </li>

                            <li class="navi-item">
                                <a href="{{ route('users.report.xlsx') }}" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="la la-file-excel-o"></i>
                                    </span>
                                    <span class="navi-text">Excel</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="#" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="la la-file-text-o"></i>
                                    </span>
                                    <span class="navi-text">CSV</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="#" class="navi-link">
                                    <span class="navi-icon">
                                        <i class="la la-file-pdf-o"></i>
                                    </span>
                                    <span class="navi-text">PDF</span>
                                </a>
                            </li>
                        </ul>
                        <!--end::Navigation-->
                    </div>
                    <!--end::Dropdown Menu-->
                </div>
                <!--end::Dropdown-->
                <!--begin::Button-->
                <a href="{{ route('parents.create') }}" class="btn btn-primary font-weight-bolder">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                                <path
                                    d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                    fill="#000000" opacity="0.3"></path>
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>New Record</a>
                <!--end::Button-->
            </div>
        </div>
        @livewire('student-parent-search-livewire', [
            'parents' => $parents,
        ])
    </div>
@endsection

@section('scripts')
    <script>
        let response = [];

        function blockManager(id) {
            const URL = 'http://127.0.0.1:8000/auto/';
            const xhr = new XMLHttpRequest();
            xhr.open("GET", URL + 'parents/' + id);
            xhr.send();
            xhr.responseType = "json";
            xhr.onload = () => {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    response = xhr.response;
                    interactWithModal(response);
                } else {
                    toastr.error('StudentParent not found.');
                }
            };
        }

        function interactWithModal(response) {
            if (response) {
                document.getElementById('modal-block-title').innerHTML = 'Info: ' + response.student_parent.fname + " " + response.student_parent.lname;
                console.log(response);

                if (!response.last_block) {
                    document.getElementById('kt_tab_pane_1_2').innerHTML = 'No data found ....';
                }
                document.getElementById('kt_tab_pane_1_2').innerHTML = `
                    <p><h5 style="display: inline-block;">Posistion:  </h5>${response.last_block.position.toUpperCase()}</p>
                    <p><h5 style="display: inline-block;">Status:  </h5>${response.last_block.status.toUpperCase()}</p>
                    <p><h5 style="display: inline-block;">Description:  </h5>${response.last_block.description}</p>
                    <p><h5 style="display: inline-block;">Blocked from:  </h5>${response.last_block.from}</p>
                    <p><h5 style="display: inline-block;">Blocked to:  </h5>${response.last_block.to}</p>
                `;
            } else {
                toastr.error('StudentParent not found.');
            }
        }

        function blockAdmin(position = 'student_parent') {
            let formData = new FormData();
            formData.append('description', document.getElementById('block_description').value);
            formData.append('from_date', document.getElementById('from_date').value);
            formData.append('to_date', document.getElementById('to_date').value);

            axios.post('/auto/block/' + response.student_parent.id + '/' + position, formData)
                .then(function(response) {
                    // handle success
                    toastr.success(response.data.message);
                })
                .catch(function(error) {
                    // handle error
                    toastr.error(error.response.data.message);
                })
                .then(function() {
                    // always executed
                });
        }
    </script>


    <script>
        function confirmDestroy(id, refrance) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    destoy(id, refrance);
                }
            })
        }

        function destoy(id, refrance) {
            axios.delete('/auto/parents/' + id)
                .then(function(response) {
                    // handle success
                    refrance.closest('tr').remove();
                    showDeletingMessage(response.data);
                })
                .catch(function(error) {
                    // handle error
                    showDeletingMessage(error.response.data);
                })
                .then(function() {
                    // always executed
                });
        }

        function showDeletingMessage(data) {
            Swal.fire({
                icon: data.icon,
                title: data.title,
                text: data.text,
                showConfirmButton: false,
                timer: 2000
            });
        }
    </script>

    @livewireScripts
@endsection
