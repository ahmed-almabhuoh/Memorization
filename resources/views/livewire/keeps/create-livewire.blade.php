<form id="creation-form">
    <div class="card-body">

        <div class="form-group">
            <label for="from_juz">Start Juz <span class="text-danger">*</span></label>
            <select class="form-control" id="from_juz" wire:model="selectedStartJuz">
                <option value="0">-- Select keep Juz --</option>
                @for($i = 1; $i <= 30; ++$i)
                    <option value="{{$i}}">{{$i}}</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label for="to_juz">End Juz <span class="text-danger">*</span></label>
            <select class="form-control" id="to_juz">
                <option value="">-- Select keep Juz --</option>
                @for($i = 1; $i <= 30; ++$i)
                    <option value="{{$i}}">{{$i}}</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label for="from_surah">Start Surah <span class="text-danger">*</span></label>
            <select class="form-control" id="from_surah">
                <option value="0">-- Select keep start surah --</option>
                @foreach ($juzs_response->surahs as $surah)
                    <option value="{{$surah->number }}">{{$surah->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="to_surah">End Surah <span class="text-danger">*</span></label>
            <select class="form-control" id="to_surah">
                <option value="">-- Select keep end surah --</option>
                @foreach ($juzs_response->surahs  as $surah)
                    <option value="{{$surah->number}}">{{$surah->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>From Ayah <span class="text-danger">*</span></label>
            <input type="number" class="form-control" placeholder="Enter keep from ayah ..." id="from_ayah"/>
        </div>

        <div class="form-group">
            <label>To Ayah <span class="text-danger">*</span></label>
            <input type="number" class="form-control" placeholder="Enter keep to ayah ..." id="to_ayah"/>
        </div>

        <div class="form-group">
            <label>Faults number <span class="text-danger">*</span></label>
            <input type=" number" class="form-control" placeholder="Enter faults number ..." id="fault_number"/>
        </div>

        <div class="card-group">
            <button type="button" onclick="store('{{\Illuminate\Support\Facades\Crypt::encrypt($student->id)}}', '{{\Illuminate\Support\Facades\Crypt::encrypt($group->id)}}')" class="btn btn-primary mr-2">Store</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</form>
