@extends('admin.pages.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">{{ __('Settings') }}</h4>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    {{-- <th>#</th> --}}
                                    <th>{{ __('Site Name') }}</th>
                                    <th>{{ __('Type Description') }}</th>
                                    <th>{{ __('Meta Description') }}</th>
                                    <th>{{ __('About Us') }}</th>
                                    <th>{{ __('Facebook Pixel') }}</th>
                                    <th>{{ __('Google Analytics') }}</th>
                                    <th>{{ __('Keywords') }}</th>
                                    <th>{{ __('Maintenance') }}</th>
                                    <th>{{ __('URL') }}</th>
                                    <th>{{ __('Logo') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($settings as $index => $setting)
                                    <tr id="settingRow{{ $setting->id }}">
                                        {{-- <td>{{ $index + 1 }}</td> --}}
                                        <td>{{ $setting->site_name }}</td>
                                        <td>{{ $setting->type_description }}</td>
                                        <td>{{ $setting->meta_description }}</td>
                                        <td>{{ $setting->about_us }}</td>
                                        <td>{{ $setting->facebook_pixel }}</td>
                                        <td>{{ $setting->google_analeteces }}</td>
                                        <td>{{ $setting->keywordes }}</td>
                                        <td>{{ $setting->maintenance_mode ? __('Enabled') : __('Disabled') }}</td>
                                        <td>{{ $setting->url }}</td>
                                        <td>
                                            @if($setting->logo)
                                                <img src="{{ asset('storage/' . $setting->logo) }}" alt="{{ __('Logo') }}" height="60">
                                            @else
                                                {{ __('N/A') }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary edit-setting"
                                                data-id="{{ $setting->id }}">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editSettingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editSettingForm" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit Setting') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">

                <div class="col-md-12">
                    <label class="form-label">{{ __('Site Name') }}</label>
                    <input type="text" name="site_name" id="edit_site_name" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('Type Description') }}</label>
                    <textarea name="type_description" id="edit_type_description" class="form-control"></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('Meta Description') }}</label>
                    <textarea name="meta_description" id="edit_meta_description" class="form-control"></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('About Us') }}</label>
                    <textarea name="about_us" id="edit_about_us" class="form-control"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('Facebook Pixel') }}</label>
                    <input type="text" name="facebook_pixel" id="edit_facebook_pixel" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('Google Analytics') }}</label>
                    <input type="text" name="google_analeteces" id="edit_google_analeteces" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('Keywords') }}</label>
                    <input type="text" name="keywordes" id="edit_keywordes" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('Maintenance') }}</label>
                    <select name="maintenance_mode" id="edit_maintenance_mode" class="form-select">
                        <option value="0">{{ __('Disabled') }}</option>
                        <option value="1">{{ __('Enabled') }}</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('URL') }}</label>
                    <input type="url" name="url" id="edit_url" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('Logo') }}</label>
                    <input type="file" name="logo" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection


@push('scripts')
<script>
$(function() {

    $('.edit-setting').click(function() {
        const id = $(this).data('id');

        $('#editSettingForm .is-invalid').removeClass('is-invalid');
        $('#editSettingForm .invalid-feedback').text('');

        $.get(`/admin/settings/${id}`, function(setting) {
            $('#edit_id').val(setting.id);
            $('#edit_site_name').val(setting.site_name);
            $('#edit_type_description').val(setting.type_description);
            $('#edit_meta_description').val(setting.meta_description);
            $('#edit_about_us').val(setting.about_us);
            $('#edit_facebook_pixel').val(setting.facebook_pixel);
            $('#edit_google_analeteces').val(setting.google_analeteces);
            $('#edit_keywordes').val(setting.keywordes);
            $('#edit_maintenance_mode').val(setting.maintenance_mode ? 1 : 0);
            $('#edit_url').val(setting.url);
            $('#editSettingModal').modal('show');
        });
    });

    $('#editSettingForm').submit(function(e) {
        e.preventDefault();
        const id = $('#edit_id').val();
        const formData = new FormData(this);

        $('#editSettingForm .is-invalid').removeClass('is-invalid');
        $('#editSettingForm .invalid-feedback').text('');

        $.ajax({
            url: `/admin/settings/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-HTTP-Method-Override': 'PUT' },
            success: function() {
                location.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function(field, messages) {
                        const input = $('#edit_' + field);
                        input.addClass('is-invalid');
                        $('#error_' + field).text(messages[0]);
                    });
                } else {
                    alert('Unexpected error occurred.');
                }
            }
        });
    });
});
</script>
@endpush
