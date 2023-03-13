<div class="card-body">
    <!--begin: Search Form-->
    <!--begin::Search Form-->


    <div class="mb-7">
        <div class="row align-items-center">
            <div class="col-lg-9 col-xl-8">
                <div class="row align-items-center">
                    <div class="col-md-4 my-2 my-md-0">
                        <div class="input-icon">
                            <input type="text" wire:model="searchTerm" class="form-control" placeholder="Search..."
                                   id="kt_datatable_search_query">
                            <span>
                                <i class="flaticon2-search-1 text-muted"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type">Type
                            <span class="text-danger">*</span></label>
                        <select class="form-control" id="type" wire:model="type">
                            <option value="all">All</option>
                            <option value="only_trashed">Only Trashed</option>
                            <option value="not_trashed">Not Trashed</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                <a href="#" class="btn btn-light-primary px-6 font-weight-bold">Search</a>
            </div>
        </div>
    </div>

    <!--end::Search Form-->
    <!--end: Search Form-->
    <!--begin: Datatable-->
    <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded"
         id="kt_datatable" style="position: static; zoom: 1;">
        <table class="datatable-table" style="display: block;">
            <thead class="datatable-head">
            <tr class="datatable-row" style="left: 0px;">
                <th data-field="Country" class="datatable-cell datatable-cell-sort"><span
                        style="width: 121px;">Name</span></th>
                <th data-field="Country" class="datatable-cell datatable-cell-sort"><span
                        style="width: 121px;">Image</span></th>
                <th data-field="CompanyName" class="datatable-cell datatable-cell-sort"><span
                        style="width: 121px;">Email</span></th>
                <th data-field="Status" class="datatable-cell datatable-cell-sort"><span
                        style="width: 121px;">Status</span></th>
                <th data-field="Type" data-autohide-disabled="false" class="datatable-cell datatable-cell-sort">
                    <span style="width: 121px;">Gender</span>
                </th>
                <th data-field="Type" data-autohide-disabled="false" class="datatable-cell datatable-cell-sort">
                    <span style="width: 121px;">Denied</span>
                </th>
                <th data-field="Actions" data-autohide-disabled="false" class="datatable-cell datatable-cell-sort">
                    <span style="width: 125px;">Actions</span>
                </th>
            </tr>
            </thead>
            <tbody class="datatable-body">
            @if (!count($keeps))
                <tr>
                    <td colspan="7">
                        No data found ...
                    </td>
                </tr>
            @endif

            <tr>
                <td colspan="7" wire:loading>
                    Search in progress ...
                </td>
            </tr>
            @foreach ($keeps as $keep)
                <tr data-row="0" class="datatable-row" style="left: 0px;">
                    <td data-field="Country" aria-label="China" class="datatable-cell"><span
                            style="width: 121px;">{{ $keep->full_name }}</span></td>
                    <td data-field="ShipDate" aria-label="8/27/2017" class="datatable-cell"><span
                            style="width: 121px;">
                                @if ($keep->image)
                                <img src="{{ Storage::url($keep->image) }}" id="keep-image" alt="-">
                            @else
                                -
                            @endif
                            </span></td>
                    <td data-field="ShipDate" aria-label="8/27/2017" class="datatable-cell"><span
                            style="width: 121px;">{{ $keep->email }}</span></td>
                    <td data-field="Status" aria-label="6" class="datatable-cell"><span style="width: 121px;"><span
                                class="{{ $keep->user_status_class }}">{{ ucfirst($keep->status) }}</span></span>
                    </td>
                    <td data-field="Type" data-autohide-disabled="false" aria-label="2" class="datatable-cell">
                            <span style="width: 121px;"><span class="label label-primary label-dot mr-2"></span><span
                                    class="{{ $keep->user_gender_class }}">{{ ucfirst($keep->gender) }}</span></span>
                    </td>


                    <td data-field="Type" data-autohide-disabled="false" aria-label="2" class="datatable-cell">
                            <span style="width: 121px;"><span
                                    class="label label-{{ $keep->user_deletion_class }} label-dot mr-2">
                                </span>
                                <span
                                    class="font-weight-bold text-{{ $keep->user_deletion_class }}">{{ $keep->user_deletion }}</span>
                            </span>
                    </td>

                    <td data-field="Actions" data-autohide-disabled="false" aria-label="null"
                        class="datatable-cell"><span style="overflow: visible; position: relative; width: 125px;">
                                <div class="dropdown dropdown-inline"> <a href="javascript:;"
                                                                          class="btn btn-sm btn-clean btn-icon mr-2"
                                                                          data-toggle="dropdown"> <span
                                            class="svg-icon svg-icon-md"> <svg xmlns="http://www.w3.org/2000/svg"
                                                                               xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                               width="24px"
                                                                               height="24px" viewBox="0 0 24 24"
                                                                               version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                   fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24"
                                                          height="24">
                                                    </rect>
                                                    <path
                                                        d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z"
                                                        fill="#000000"></path>
                                                </g>
                                            </svg> </span> </a>
                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                        <ul class="navi flex-column navi-hover py-2">
                                            <li
                                                class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">
                                                Choose an action: </li>
                                            <li class="navi-item"> <a
                                                    href="#"
                                                    class="navi-link"> <span class="navi-icon"><i
                                                            class="la la-print"></i></span> <span
                                                        class="navi-text">Block list</span> </a> </li>
                                            <li class="navi-item">
                                                <a onclick="blockManager('{{ Crypt::encrypt($keep->id) }}')"
                                                   class="navi-link" data-toggle="modal"
                                                   data-target="#exampleModalCustomScrollable"> <span
                                                        class="navi-icon"><i class="la la-copy"></i></span> <span
                                                        class="navi-text">Settings</span> </a>
                                            </li>
                                            <li class="navi-item"> <a
                                                    href="{{ route('user.report.xlsx', Crypt::encrypt($keep->id)) }}"
                                                    class="navi-link"> <span class="navi-icon"><i
                                                            class="la la-file-excel-o"></i></span>
                                                    <span class="navi-text">Excel</span> </a> </li>
                                            <li class="navi-item"> <a href="#" class="navi-link"> <span
                                                        class="navi-icon"><i class="la la-file-text-o"></i></span>
                                                    <span class="navi-text">CSV</span> </a> </li>
                                            <li class="navi-item"> <a href="#" class="navi-link"> <span
                                                        class="navi-icon"><i class="la la-file-pdf-o"></i></span>
                                                    <span class="navi-text">PDF</span> </a> </li>
                                        </ul>
                                    </div>
                                </div> <a href="{{ route('keeps.index', [
    'student_id' => \Illuminate\Support\Facades\Crypt::encrypt($student->id),
    'group_id' => \Illuminate\Support\Facades\Crypt::encrypt($group->id),
]) }}"
                                          class="btn btn-sm btn-clean btn-icon mr-2" title="Edit details"> <span
                                    class="svg-icon svg-icon-md"> <svg xmlns="http://www.w3.org/2000/svg"
                                                                       xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                       width="24px" height="24px"
                                                                       viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24">
                                                </rect>
                                                <path
                                                    d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z"
                                                    fill="#000000" fill-rule="nonzero"
                                                    transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) ">
                                                </path>
                                                <rect fill="#000000" opacity="0.3" x="5" y="20"
                                                      width="15" height="2" rx="1">
                                                </rect>
                                            </g>
                                        </svg> </span> </a> <button type="button"
                                                                    onclick="confirmDestroy('{{ \Illuminate\Support\Facades\Crypt::encrypt($keep->id) }}', this)"
                                                                    class="btn btn-sm btn-clean btn-icon"
                                                                    title="Delete"> <span
                                    class="svg-icon svg-icon-md"> <svg xmlns="http://www.w3.org/2000/svg"
                                                                       xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                       width="24px" height="24px"
                                                                       viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24">
                                                </rect>
                                                <path
                                                    d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z"
                                                    fill="#000000" fill-rule="nonzero"></path>
                                                <path
                                                    d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                                    fill="#000000" opacity="0.3"></path>
                                            </g>
                                        </svg> </span> </button>
                            </span></td>
                </tr>

                <div class="modal fade" id="exampleModalCustomScrollable" tabindex="-1" role="dialog"
                     aria-labelledby="staticBackdrop" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-block-title"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div data-scroll="true" data-height="300">

                                    <div class="card card-custom">
                                        <div class="card-header card-header-tabs-line">
                                            <div class="card-title">
                                                <h3 class="card-label">Related</h3>
                                            </div>
                                            <div class="card-toolbar">
                                                <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-toggle="tab"
                                                           href="#kt_tab_pane_1_2">Last Block</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab"
                                                           href="#kt_tab_pane_2_2">Block</a>
                                                    </li>
                                                    <li class="nav-item dropdown">
                                                        <a class="nav-link dropdown-toggle" data-toggle="dropdown"
                                                           href="#" role="button" aria-haspopup="true"
                                                           aria-expanded="false">Details</a>
                                                        <div
                                                            class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                            <a class="dropdown-item" data-toggle="tab"
                                                               href="#kt_tab_pane_3_2">About</a>
                                                            {{-- <a class="dropdown-item" data-toggle="tab"
                                                                href="#kt_tab_pane_3_2">Another action</a>
                                                            <a class="dropdown-item" data-toggle="tab"
                                                                href="#kt_tab_pane_3_2">Something else here</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item" data-toggle="tab"
                                                                href="#kt_tab_pane_3_2">Separated link</a> --}}
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="kt_tab_pane_1_2"
                                                     role="tabpanel">

                                                </div>
                                                <div class="tab-pane fade" id="kt_tab_pane_2_2" role="tabpanel">

                                                    <form action="">
                                                        <div class="form-group mb-1">
                                                            <label for="block_description">Block
                                                                description</label>
                                                            <textarea class="form-control" id="block_description"
                                                                      rows="5"></textarea>
                                                        </div>

                                                        <div class="form-group mb-1">
                                                            <label for="from_date">Block from</label>
                                                            <input class="form-control" type="datetime-local"
                                                                   value="2011-08-19T13:45:00" id="from_date"/>
                                                        </div>

                                                        <div class="form-group mb-1">
                                                            <label for="to_date">Block to</label>
                                                            <input class="form-control" type="datetime-local"
                                                                   value="2011-08-19T13:45:00" id="to_date"/>
                                                        </div>
                                                    </form>

                                                </div>
                                                <div class="tab-pane fade" id="kt_tab_pane_3_2" role="tabpanel">
                                                    Third
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-primary font-weight-bold"
                                                data-dismiss="modal">Close
                                        </button>
                                        <button type="button" onclick="blockKeep('keep')"
                                                class="btn btn-primary font-weight-bold">Save
                                            changes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </tbody>
        </table>
        <div class="datatable-pager datatable-paging-loaded">
            {{ $keeps->links() }}
        </div>
    </div>
    <!--end: Datatable-->

</div>
