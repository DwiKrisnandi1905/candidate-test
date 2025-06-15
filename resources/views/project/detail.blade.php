<x-app-layout>
    @section('breadcrumb')
        <x-breadcrumb :items="[
            ['label' => 'Projects', 'url' => route('project.index')],
            ['label' => $project->project_name, 'url' => route('project.show', $project->id)]
        ]" />
    @endsection
    <div class="py-4 px-6">
        <h2 class="text-3xl font-semibold mb-4">Project Detail</h2>
        <h4 class="font-semibold mb-4" style="color: #578F73;">Manage project components and details.</h4>

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '{{ session('success') }}',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            </script>
        @endif

        <!-- form edit & update project -->
        <form action="{{ route('project.update', $project->id) }}" method="POST" class="max-w-md">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-medium">Project Name</label>
                <input type="text" name="project_name" value="{{ old('project_name', $project->project_name) }}" class="w-full border px-3 py-2 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Project Description</label>
                <textarea name="project_description" rows="4" class="w-full border px-3 py-2 rounded">{{ old('project_description', $project->project_description) }}</textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        </form>

        <!-- Button trigger modal -->
       <div class="flex justify-between items-center mb-2">
            <h2 class="text-2xl font-semibold mt-6">Building Parts</h2>
            <button id="btnAddPart" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mt-6">
                + Add Building Part
            </button>
        </div>


        <!-- Table list building parts -->
        <table id="buildingPartsTable" class="min-w-full table-auto border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Name Building</th>
                    <th class="p-2 border">Part Type</th>
                    <th class="p-2 border">Material</th>
                    <th class="p-2 border">Supplier</th>
                    <th class="p-2 border">Action</th>
                </tr>
            </thead>
        </table>

        <!-- Modal Add -->
        <div id="modalForm" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center">
            <div class="bg-white p-6 rounded shadow-md w-full max-w-lg">
                <h3 class="text-xl font-semibold mb-4">Add Building Part</h3>
                <form id="buildingPartForm">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="mb-4">
                        <label class="block">Name Building</label>
                        <input type="text" name="name_building" class="w-full border px-3 py-2 rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block">Building Part Type</label>
                        <select name="building_part_type" id="building_part_type" class="w-full border px-3 py-2 rounded" required>
                            <option value="">-- Select --</option>
                            <option value="floor">Floor</option>
                            <option value="wall">Wall</option>
                            <option value="beam">Beam</option>
                            <option value="column">Column</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block">Material</label>
                        <select name="material" id="material" class="w-full border px-3 py-2 rounded" required>
                            <option value="">-- Select --</option>
                            <option value="clt">CLT</option>
                            <option value="glt">GLT</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block">Supplier</label>
                        <select name="supplier" id="supplier" class="w-full border px-3 py-2 rounded" required>
                            <option value="">-- Select material first --</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" id="cancelModal" class="px-4 py-2 mr-2 border rounded">Cancel</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div id="modalEdit" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center">
            <div class="bg-white p-6 rounded shadow-md w-full max-w-lg">
                <h3 class="text-xl font-semibold mb-4">Edit Building Part</h3>
                <form id="buildingPartEditForm">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit_id">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="mb-4">
                        <label class="block">Name Building</label>
                        <input type="text" name="name_building" id="edit_name_building" class="w-full border px-3 py-2 rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block">Building Part Type</label>
                        <select name="building_part_type" id="edit_building_part_type" class="w-full border px-3 py-2 rounded" required>
                            <option value="">-- Select --</option>
                            <option value="floor">Floor</option>
                            <option value="wall">Wall</option>
                            <option value="beam">Beam</option>
                            <option value="column">Column</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block">Material</label>
                        <select name="material" id="edit_material" class="w-full border px-3 py-2 rounded" required>
                            <option value="">-- Select --</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block">Supplier</label>
                        <select name="supplier" id="edit_supplier" class="w-full border px-3 py-2 rounded" required>
                            <option value="">-- Select --</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" id="cancelEditModal" class="px-4 py-2 mr-2 border rounded">Cancel</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // Mapping tipe ke material
            const validMaterialMap = {
                floor: ['clt'],
                wall: ['clt'],
                beam: ['clt', 'glt'],
                column: ['glt'],
            };

            function generateMaterialOptions(type, selected = '') {
                const materials = validMaterialMap[type] || [];
                let html = '<option value="">-- Select --</option>';
                materials.forEach(mat => {
                    const isSelected = mat === selected ? 'selected' : '';
                    html += `<option value="${mat}" ${isSelected}>${mat.toUpperCase()}</option>`;
                });
                return html;
            }

            // ================= ADD MODAL =================
            $('#btnAddPart').on('click', function () {
                $('#modalForm').removeClass('hidden').addClass('flex');
            });

            $('#cancelModal').on('click', function () {
                $('#modalForm').removeClass('flex').addClass('hidden');
                $('#buildingPartForm')[0].reset();
                $('#material').html('<option value="">-- Select building part first --</option>');
                $('#supplier').html('<option>-- Select material first --</option>');
            });

            $('#building_part_type').on('change', function () {
                const type = $(this).val();
                $('#material').html(generateMaterialOptions(type));
                $('#supplier').html('<option>-- Select material first --</option>');
            });

            $('#material').on('change', function () {
                const type = $('#building_part_type').val();
                const mat = $(this).val();
                if (!validMaterialMap[type]?.includes(mat)) {
                    alert("Invalid material for selected type");
                    $(this).val('');
                    $('#supplier').html('<option>-- Select material first --</option>');
                } else {
                    fetchSuppliers(mat, '#supplier');
                }
            });

            //fetch api supplier
            function fetchSuppliers(material, selector, selectedSupplier = null) {
                $.get('/api/suppliers', function (data) {
                    let options = '<option value="">-- Select --</option>';
                    data.forEach(supplier => {
                        if (supplier.material_type === material) {
                            const selected = supplier.name === selectedSupplier ? 'selected' : '';
                            options += `<option value="${supplier.name}" ${selected}>${supplier.name}</option>`;
                        }
                    });
                    $(selector).html(options);
                });
            }

            $('#buildingPartForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('project.detail.store', $project->id) }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message || 'Building part added successfully!',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('#modalForm').addClass('hidden');
                        $('#buildingPartForm')[0].reset();
                        $('#material').html('<option value="">-- Select building part first --</option>');
                        $('#supplier').html('<option>-- Select material first --</option>');
                        $('#buildingPartsTable').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to save building part.',
                        });
                    }
                });
            });

            // ================= SHOW DATA BUILDING PART =================
            $('#buildingPartsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("project.detail") }}?project_id={{ $project->id }}',
                pageLength: 10,
                columns: [
                    { data: 'name_building' },
                    { data: 'building_part_type' },
                    { data: 'material' },
                    { data: 'supplier' },
                    {
                        data: null,
                        render: function (data) {
                            return `
                                <button class="edit-btn text-blue-600" data-id="${data.id}">Edit</button> |
                                <button class="delete-btn text-red-600" data-id="${data.id}">Delete</button>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // ================= EDIT MODAL =================
            $('#buildingPartsTable').on('click', '.edit-btn', function () {
                const id = $(this).data('id');
                $.get(`/project/detail/edit/${id}`, function (res) {
                    $('#edit_id').val(res.id);
                    $('#edit_name_building').val(res.name_building);
                    $('#edit_building_part_type').val(res.building_part_type);

                    // Set material options based on type
                    $('#edit_material').html(generateMaterialOptions(res.building_part_type, res.material));
                    fetchSuppliers(res.material, '#edit_supplier', res.supplier);

                    setTimeout(() => {
                        $('#edit_material').val(res.material);
                        // $('#edit_supplier').val(res.supplier);
                    }, 200);

                    $('#modalEdit').removeClass('hidden').addClass('flex');
                });
            });

            $('#edit_building_part_type').on('change', function () {
                const type = $(this).val();
                $('#edit_material').html(generateMaterialOptions(type));
                $('#edit_supplier').html('<option>-- Select material first --</option>');
            });

            $('#edit_material').on('change', function () {
                const type = $('#edit_building_part_type').val();
                const mat = $(this).val();
                if (!validMaterialMap[type]?.includes(mat)) {
                    alert("Invalid material for selected type");
                    $(this).val('');
                    $('#edit_supplier').html('<option>-- Select material first --</option>');
                } else {
                    fetchSuppliers(mat, '#edit_supplier');
                }
            });

            $('#buildingPartEditForm').on('submit', function (e) {
                e.preventDefault();
                const id = $('#edit_id').val();

                $.ajax({
                    url: `/project/detail/update/${id}`,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: response.message || 'Building part updated successfully.',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('#modalEdit').removeClass('flex').addClass('hidden');
                        $('#buildingPartEditForm')[0].reset();
                        $('#buildingPartsTable').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: xhr.responseJSON?.message || 'An error occurred during update.',
                        });
                    }
                });
            });

            $('#cancelEditModal').on('click', function () {
                $('#modalEdit').removeClass('flex').addClass('hidden');
                $('#buildingPartEditForm')[0].reset();
            });

            // ================= DELETE =================
            $('#buildingPartsTable').on('click', '.delete-btn', function () {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/project/detail/delete/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message || 'Building part has been deleted.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                $('#buildingPartsTable').DataTable().ajax.reload();
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed!',
                                    text: xhr.responseJSON?.message || 'Failed to delete building part.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</x-app-layout>
