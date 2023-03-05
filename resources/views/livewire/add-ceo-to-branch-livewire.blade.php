<div class="card-body">
    <!--begin: Search Form-->
    <!--begin::Search Form-->
    <div class="mb-7">
        <div class="row align-items-center">
            <div class="col-lg-9 col-xl-8">
                <div class="row align-items-center">
                    <div class="col-md-4 my-2 my-md-0">
                        <div class="input-icon">
                            <input type="text" wire:model="searchTerm" class="form-control" placeholder="Search ..."
                                id="kt_datatable_search_query">
                            <span>
                                <i class="flaticon2-search-1 text-muted"></i>
                            </span>
                        </div>
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
                            style="width: 121px;">Image</span></th>
                    <th data-field="Country" class="datatable-cell datatable-cell-sort"><span
                            style="width: 121px;">Name</span></th>
                    <th data-field="CompanyName" class="datatable-cell datatable-cell-sort"><span
                            style="width: 121px;">Identity No.</span></th>
                    <th data-field="Actions" data-autohide-disabled="false" class="datatable-cell datatable-cell-sort">
                        <span style="width: 125px;">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="datatable-body" style="">
                @if (!count($supervisors))
                    <tr>
                        <td colspan="5">
                            <p>No data found ...</p>
                        </td>
                    </tr>
                @endif
                @foreach ($supervisors as $supervisor)
                    <tr data-row="0" class="datatable-row" style="left: 0px;">
                        <td data-field="ShipDate" aria-label="8/27/2017" class="datatable-cell"><span
                                style="width: 121px;">
                                @if ($supervisor->image)
                                    <img src="{{ Storage::url($supervisor->image) }}" id="supervisor-image"
                                        alt="-">
                                @else
                                    -
                                @endif
                            </span></td>

                        <td data-field="Country" aria-label="China" class="datatable-cell"><span
                                style="width: 121px;">{{ $supervisor->full_name }}</span></td>

                        <td data-field="ShipDate" aria-label="8/27/2017" class="datatable-cell"><span
                                style="width: 121px;">{{ $supervisor->identity_no }}</span></td>


                        <td data-field="Actions" data-autohide-disabled="false" aria-label="null"
                            class="datatable-cell"><span style="overflow: visible; position: relative; width: 125px;">
                                <label class="checkbox checkbox-primary">
                                    <input type="checkbox" name="supervisor_{{ $supervisor->id }}"
                                        @if ($supervisor->id === $branch->supervisor_id) checked @endif
                                        id="supervisor_{{ $supervisor->id }}"
                                        onchange="add('{{ Crypt::encrypt($branch->id) }}', '{{ Crypt::encrypt($supervisor->id) }}')">
                                    <span></span>
                                </label>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="datatable-pager datatable-paging-loaded">
            {{ $supervisors->links() }}
        </div>
    </div>
    <!--end: Datatable-->
</div>
