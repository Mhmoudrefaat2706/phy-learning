@extends('admin.pages.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">{{ __('Social Media Links') }}</h4>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createSocialModal">
                    <i class="bi bi-plus-lg me-1"></i> {{ __('Add Social Media') }}
                </button>
            </div>

            <!-- Toast container -->
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
                <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage">
                            {{ __('Success message') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="{{ __('Close') }}"></button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="socialTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width:60px">#</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('URL') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-center" style="width:180px">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($socials as $index => $social)
                                    <tr id="socialRow{{ $social->id }}">
                                        <td class="text-center fw-bold">{{ $index+1 }}</td>
                                        <td class="social-name">{{ $social->name }}</td>
                                        <td class="social-url">{{ $social->url }}</td>
                                        <td class="social-status">{{ $social->status }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary edit-social" data-id="{{ $social->id }}">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-social" data-id="{{ $social->id }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3 px-5">
                            {{ $socials->links("pagination::bootstrap-5") }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ===== Create Modal ===== --}}
<div class="modal fade" id="createSocialModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Social Media') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createSocialForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="createName" required>
                        <div class="invalid-feedback" id="createNameError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('URL') }}</label>
                        <input type="url" class="form-control" id="createUrl" required>
                        <div class="invalid-feedback" id="createUrlError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select class="form-select" id="createStatus" required>
                            <option value="active" selected>{{ __('Active') }}</option>
                            <option value="inactive">{{ __('Inactive') }}</option>
                        </select>
                        <div class="invalid-feedback" id="createStatusError"></div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ===== Edit Modal ===== --}}
<div class="modal fade" id="editSocialModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit Social Media') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editSocialForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="modalSocialId">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="editName" required>
                        <div class="invalid-feedback" id="editNameError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('URL') }}</label>
                        <input type="url" class="form-control" id="editUrl" required>
                        <div class="invalid-feedback" id="editUrlError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select class="form-select" id="editStatus" required>
                            <option value="active">{{ __('Active') }}</option>
                            <option value="inactive">{{ __('Inactive') }}</option>
                        </select>
                        <div class="invalid-feedback" id="editStatusError"></div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ===== Delete Modal ===== --}}
<div class="modal fade" id="deleteSocialModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">{{ __('Confirm Delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Delete') }} <strong id="deleteSocialName"></strong> ?</p>
                <input type="hidden" id="deleteSocialId">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteSocialBtn">{{ __('Delete') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const createForm = document.getElementById('createSocialForm');
    const editForm   = document.getElementById('editSocialForm');
    const createModal = new bootstrap.Modal('#createSocialModal');
    const editModal   = new bootstrap.Modal('#editSocialModal');
    const deleteModal = new bootstrap.Modal('#deleteSocialModal');
    const toastEl     = document.getElementById('liveToast');
    const toastBody   = document.getElementById('toastMessage');
    const toast       = new bootstrap.Toast(toastEl);

    function showToast(message){
        toastBody.textContent = message;
        toast.show();
    }

    function resetErrors(ids){
        ids.forEach(id=>{
            document.getElementById(id).classList.remove('is-invalid');
            document.getElementById(id+'Error').textContent='';
        });
    }

    // === Create ===
    createForm.addEventListener('submit', e=>{
        e.preventDefault();
        resetErrors(['createName','createUrl','createStatus']);
        fetch("{{ route('admin.social_media.store') }}",{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({
                name: document.getElementById('createName').value.trim(),
                url:  document.getElementById('createUrl').value.trim(),
                status: document.getElementById('createStatus').value
            })
        }).then(async res=>{
            const data = await res.json();
            if(res.ok){
                const tBody = document.querySelector('#socialTable tbody');
                const index = tBody.rows.length + 1;
                tBody.insertAdjacentHTML('beforeend',`
                <tr id="socialRow${data.social.id}">
                    <td class="text-center fw-bold">${index}</td>
                    <td class="social-name">${data.social.name}</td>
                    <td class="social-url">${data.social.url}</td>
                    <td class="social-status">${data.social.status}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-secondary edit-social" data-id="${data.social.id}">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-social" data-id="${data.social.id}">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>`);
                createForm.reset();
                createModal.hide();
                showToast('Social media link added successfully.');
            }else if(res.status===422){
                Object.entries(data.errors).forEach(([key,val])=>{
                    const map = {name:'createName', url:'createUrl', status:'createStatus'};
                    const inputId = map[key];
                    if(inputId){
                        document.getElementById(inputId).classList.add('is-invalid');
                        document.getElementById(inputId+'Error').textContent = val[0];
                    }
                });
            }
        });
    });

    // === Edit ===
    document.addEventListener('click', e=>{
        if(e.target.closest('.edit-social')){
            const id = e.target.closest('.edit-social').dataset.id;
            const row = document.getElementById('socialRow'+id);
            document.getElementById('modalSocialId').value = id;
            document.getElementById('editName').value = row.querySelector('.social-name').textContent;
            document.getElementById('editUrl').value  = row.querySelector('.social-url').textContent;
            document.getElementById('editStatus').value = row.querySelector('.social-status').textContent;
            resetErrors(['editName','editUrl','editStatus']);
            editModal.show();
        }
        if(e.target.closest('.delete-social')){
            const id = e.target.closest('.delete-social').dataset.id;
            const row = document.getElementById('socialRow'+id);
            document.getElementById('deleteSocialId').value = id;
            document.getElementById('deleteSocialName').textContent = row.querySelector('.social-name').textContent;
            deleteModal.show();
        }
    });

    editForm.addEventListener('submit', e=>{
        e.preventDefault();
        resetErrors(['editName','editUrl','editStatus']);
        const id  = document.getElementById('modalSocialId').value;
        fetch(`/admin/social-media/${id}`,{
            method:'PUT',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({
                name: document.getElementById('editName').value.trim(),
                url:  document.getElementById('editUrl').value.trim(),
                status: document.getElementById('editStatus').value
            })
        }).then(async res=>{
            const data = await res.json();
            if(res.ok){
                const row = document.getElementById('socialRow'+id);
                row.querySelector('.social-name').textContent = data.social.name;
                row.querySelector('.social-url').textContent  = data.social.url;
                row.querySelector('.social-status').textContent  = data.social.status;
                editModal.hide();
                showToast('Social media link updated successfully.');
            }else if(res.status===422){
                Object.entries(data.errors).forEach(([key,val])=>{
                    const map = {name:'editName', url:'editUrl', status:'editStatus'};
                    const inputId = map[key];
                    if(inputId){
                        document.getElementById(inputId).classList.add('is-invalid');
                        document.getElementById(inputId+'Error').textContent = val[0];
                    }
                });
            }
        });
    });

    // === Delete ===
    document.getElementById('confirmDeleteSocialBtn').addEventListener('click', ()=>{
        const id = document.getElementById('deleteSocialId').value;
        fetch(`/admin/social-media/${id}`,{
            method:'DELETE',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
        }).then(res=>res.json()).then(data=>{
            if(data.success){
                document.getElementById('socialRow'+id).remove();
                deleteModal.hide();
                showToast('Social media link deleted successfully.');
            }
        });
    });
});
</script>
@endpush
