<x-main-layout title="مؤسسة سبيلي الخيرية">

    @push('styles')

    <style>
        table , td , th ,tr{
            border: none !important;
            border-bottom: none !important;
        }

        .table-info{
               --bs-table-bg: var(--third-color);
               border-top-right-radius: 10px;
               border-top-left-radius: 10px;
        }
        .intro2 {
            display: none;
        }

        .intro2.show {
            display: flex;
        }
    </style>

    @endpush

    <section class="mt-1">

        <x-alert name="success" />
        <x-alert name="danger" />

        {{-- section header component --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

            <div>
                <h3 class="mb-1"> الأيتام المعتمدون </h3>
                <p style="color: rgba(36, 36, 36, 0.6);font-size:16px">
                   هذه القائمة تحتوي على جميع الأيتام الذين تم اعتماد بياناتهم وهم مؤهلون للكفالة.
                </p>
            </div>

            <a href="{{route('orphans.action.search')}}" class="submit-btn text-decoration-none rounded" style="padding-right:60px;padding-left:60px"> إنشاء استعلام </a>

        </div>

        <div class="intro2 mb-3  flex-warp gap-2 align-items-center" id="actionButtons">

            {{-- sponsor button --}}
            <a href="#" id="openSponsorshipModal" class="btn text-white" style="background-color: rgba(59, 207, 112, 1);">
                <img src="{{asset('assets/icon/true.png')}}" alt="" class="mb-1">
                <span>  إحالة للكفالة  </span>
            </a>

            <!--sponsor Modal -->
            <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel1" aria-hidden="true">
                <div class="modal-dialog  modal-md modal-dialog-centered">
                    <div class="modal-content p-4">
                        <div class="modal-header justify-content-center border-bottom-0">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel1"> بيانات الكفالة </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body border-bottom-0">
                            <form action="{{ route('orphans.action.sponsor') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="ids" id="selectedOrphanIds">

                                <div class="mb-3">
                                    <x-form.input name="amount" type="text" label=" قيمة الكفالة الشهرية " id="amount"/>
                                </div>


                                <div class="mb-3">
                                    <x-form.input name="duration" type="number" label=" مدة الكفالة (بالشهر) "  id="duration"/>
                                </div>


                                <button type="submit" class="submit-btn mt-3 w-100">تأكيد</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>


            <div>
                <button class="submit btn-delete btn btn-danger delete-selected">
                    <img src="{{asset('assets/icon/delete.png')}}" alt="" class="mb-1">
                    <span> حذف المحدد </span>
                </button>

                <form id="deleteForm" action="{{route('orphans.action.delete')}}" method="post" style="display: none" >
                    @csrf
                    @method('delete')
                    <input type="hidden" name="delete_ids" id="selected-delete-ids">
                </form>
            </div>


        </div>

        <div class="table-responsive">
            <table class="table align-middle">

                <thead class="table-info">
                    <tr>
                        <th style="border-top-right-radius:15px"></th>
                        <th>الاسم</th>
                        <th>رقم الهوية</th>
                        <th>رقم كود اليتيم</th>
                        <th>العمر</th>
                        <th>الجنس</th>
                        <th>العنوان</th>
                        {{-- <th>حالة اليتم</th> --}}
                        <th style="border-top-left-radius:15px">الإجراءات</th>

                    </tr>
                </thead>

                <tbody>


                    @forelse ($orphans as $orphan )

                        <tr>
                            <td> <input type="checkbox" name="select_ids[]" class="orphan-checkbox" value="{{$orphan->id}}" > </td>
                            <td> <a href="{{route('orphan.show' , $orphan->id)}}" class="text-decoration-none" style="cursor: pointer">  {{$orphan->name}} </a> </td>
                            <td> {{$orphan->id_number}} </td>
                            <td> {{$orphan->orphan_code}} </td>
                            <td> {{$orphan->age}} سنوات </td>
                            <td> {{$orphan->gender}} </td>
                            <td> {{$orphan->address}}</td>
                            {{-- <td> يتيم الأب </td> --}}
                            <td class="d-flex flex-wrap gap-1">


                                <button class="text-decoration-none mb-1 btn btn-outline-success sponorship-btn"  data-bs-toggle="modal" data-id="{{ $orphan->id }}" data-bs-target="#singleWaitModal"> إحالة للكفالة  </button>


                                <div>
                                    <button  class="submit d-flex btn-delete btn btn-outline-danger" >
                                        حذف
                                    </button>

                                    <form  action="{{route('orphans.action.delete')}}" method="post" style="display: none">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" name="delete_ids" value="{{ $orphan->id }}">

                                    </form>
                                </div>

                            </td>

                        </tr>

                    @empty

                        <td colspan="8" class="fs-4 text-center" style="color: var(--primary-color)">  لا يوجد ايتام معتمدين مسجلين في النظام </td>

                    @endforelse



                    <div class="modal fade" id="singleWaitModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="singleWaitModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content p-5">

                                <div class="modal-header justify-content-center border-bottom-0">
                                    <h1 class="modal-title fs-5" id="singleWaitModalLabel"> بيانات الكفالة  </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>



                                <div class="modal-body border-bottom-0">
                                    <form id="singleWaitingForm" action="{{ route('orphans.action.sponsor') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="ids" id="singleOrphanId">

                                        <x-form.input name="amount"  type="text"  label=" قيمة الكفالة الشهرية "  class="mb-3"/>

                                        <x-form.input name="duration"  type="number"  label="(بالشهر) المدة "  min="0" />

                                        <button type="submit" class="submit-btn mt-3 w-100">تأكيد </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>




                </tbody>

            </table>
        </div>


    </section>

    @push('scripts')

        <script>

            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('.orphan-checkbox');
                const actionButtons = document.getElementById('actionButtons');
                // const selectedIdsInput = document.getElementById('selected-ids');
                const selectedDeleteIdsInput = document.getElementById('selected-delete-ids');

                let selectedIds = [];

                // تحديث ظهور أزرار الإجراءات
                function toggleActionButtons() {
                    const checkedBoxes = document.querySelectorAll('.orphan-checkbox:checked');
                    if (checkedBoxes.length > 0) {
                        actionButtons.classList.add('show');
                    } else {
                        actionButtons.classList.remove('show');
                    }
                }

                // تهيئة selectedIds بناءً على checkboxes المختارة عند تحميل الصفحة
                function initializeSelectedIds() {
                    selectedIds = [];
                    checkboxes.forEach(checkbox => {
                        if (checkbox.checked) {
                            selectedIds.push(checkbox.value);
                        }
                    });
                    // selectedIdsInput.value = selectedIds.join(',');
                    selectedDeleteIdsInput.value = selectedIds.join(',');
                }

                // استدعاء التهيئة الأولى عند التحميل
                initializeSelectedIds();
                toggleActionButtons();

                // حدث تغيير في أي checkbox
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        const id = this.value;

                        if (this.checked) {
                            if (!selectedIds.includes(id)) {
                                selectedIds.push(id);
                            }
                        } else {
                            selectedIds = selectedIds.filter(item => item !== id);
                        }

                        // selectedIdsInput.value = selectedIds.join(',');
                        selectedDeleteIdsInput.value = selectedIds.join(',');

                        toggleActionButtons();
                    });
                });
            });

        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const waitButtons = document.querySelectorAll('.sponorship-btn');
                const orphanIdInput = document.getElementById('singleOrphanId');

                waitButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const orphanId = this.getAttribute('data-id');
                        orphanIdInput.value = orphanId;
                    });
                });
            });
        </script>

        <script>
            document.getElementById('openSponsorshipModal').addEventListener('click', function (e) {
                e.preventDefault();

                const selectedCheckboxes = document.querySelectorAll('.orphan-checkbox:checked');
                let ids = [];

                selectedCheckboxes.forEach(cb => {
                    ids.push(cb.value);
                });

                document.getElementById('selectedOrphanIds').value = ids.join(',');

                // افتح المودال يدويًا
                const emailModal = new bootstrap.Modal(document.getElementById('staticBackdrop1'));
                emailModal.show();
            });
        </script>

    @endpush

    {{$orphans->withQueryString()->links()}}
</x-main-layout>

