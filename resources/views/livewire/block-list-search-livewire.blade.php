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
                            style="width: 121px;">ID</span></th>
                    <th data-field="Country" class="datatable-cell datatable-cell-sort"><span
                            style="width: 121px;">DESC</span></th>
                    <th data-field="CompanyName" class="datatable-cell datatable-cell-sort"><span
                            style="width: 121px;">FROM</span></th>
                    <th data-field="Status" class="datatable-cell datatable-cell-sort"><span
                            style="width: 121px;">TO</span></th>
                    <th data-field="Type" data-autohide-disabled="false" class="datatable-cell datatable-cell-sort">
                        <span style="width: 121px;">STATUS</span>
                    </th>
                </tr>
            </thead>
            <tbody class="datatable-body" style="">
                @if (!count($blocks))
                    <tr>
                        <td colspan="5" style="">
                            <p style="">No data found ...</p>
                        </td>
                    </tr>
                @endif
                @php
                    $counter = 1;
                @endphp
                @foreach ($blocks as $block)
                    <tr data-row="0" class="datatable-row" style="left: 0px;">
                        <td data-field="Country" aria-label="China" class="datatable-cell"><span
                                style="width: 121px;">{{ $counter; }}</span></td>
                        <td data-field="ShipDate" aria-label="8/27/2017" class="datatable-cell"><span
                                style="width: 121px;">
                                {{ ucfirst($block->description) ?? '-' }}
                            </span></td>
                        <td data-field="ShipDate" aria-label="8/27/2017" class="datatable-cell"><span
                                style="width: 121px;">{{ $block->from ?? '-' }}</span></td>
                        <td data-field="ShipDate" aria-label="8/27/2017" class="datatable-cell"><span
                                style="width: 121px;">{{ $block->to ?? '-' }}</span></td>
                        <td data-field="Status" aria-label="6" class="datatable-cell"><span style="width: 121px;"><span
                                    class="{{ $block->block_status_class }}">{{ ucfirst($block->status) }}</span></span>
                        </td>
                    </tr>
                    @php
                        ++$counter;
                    @endphp
                @endforeach
            </tbody>
        </table>
        <div class="datatable-pager datatable-paging-loaded">
            {{ $blocks->links() }}
        </div>
    </div>
    <!--end: Datatable-->
</div>
