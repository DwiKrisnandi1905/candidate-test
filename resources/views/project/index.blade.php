{{-- resources/views/project.blade.php --}}
<x-app-layout>
    @section('breadcrumb')
        <x-breadcrumb :items="[['label' => 'Projects', 'url' => route('project.index')]]" />
    @endsection
    <div class="py-4 px-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Project List</h2>
            <button id="addProjectBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Add New</button>
        </div>

        <!-- Table list project -->
        <table id="projectTable" class="min-w-full table-auto border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Project Name</th>
                    <th class="px-4 py-2 border">Description</th>
                    <th class="px-4 py-2 border">Action</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Modal add building parts -->
    <div id="projectModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Add New Project</h3>
            <form id="projectForm">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Project Name</label>
                    <input type="text" name="project_name" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Project Description</label>
                    <textarea name="project_description" rows="3" class="w-full border px-3 py-2 rounded"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="button" id="closeModal" class="mr-2 px-4 py-2 border rounded">Cancel</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // ================= SHOW DATA PROJECT =================
            let table = $('#projectTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('project.data') }}",
                pageLength: 10,
                columns: [
                    { data: 'project_name', name: 'project_name' },
                    { data: 'project_description', name: 'project_description' },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return `
                                <a href="/project/detail/${data}" class="text-blue-600 hover:underline mr-2">View</a> |
                                <button data-id="${data}" class="delete-project-btn text-red-600 hover:underline ml-2">Delete</button>
                            `;
                        }
                    }
                ]
            });

            // ================= MODAL ADD PROJECT =================
            // Show modal
            $('#addProjectBtn').on('click', function () {
                $('#projectModal').removeClass('hidden').addClass('flex');
            });

            // Close modal
            $('#closeModal').on('click', function () {
                $('#projectModal').removeClass('flex').addClass('hidden');
                $('#projectForm')[0].reset();
            });

            // ================= SAVE PROJECT =================
            // Submit form
            $('#projectForm').on('submit', function (e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('project.store') }}",
                    method: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Project saved!',
                                text: response.message || 'Project has been successfully saved.',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $('#projectModal').addClass('hidden');
                            $('#projectForm')[0].reset();
                            $('#projectTable').DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Save failed',
                                text: response.message || 'Failed to save project.',
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while saving the project.',
                        });
                    }
                });
            });

            // ================= DELETE PROJECT =================
            $('#projectTable').on('click', '.delete-project-btn', function () {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "All related building parts will also be deleted.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/project/delete/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message || 'Project has been deleted.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                $('#projectTable').DataTable().ajax.reload();
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed!',
                                    text: xhr.responseJSON?.message || 'Failed to delete project.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</x-app-layout>
