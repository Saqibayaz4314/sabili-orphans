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

    </style>

    @endpush

    <section class="mt-1">

        <x-alert name="success" />
        <x-alert name="danger" />

        {{-- section header component --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

            <div>
                <h3 class="mb-1">  الموظفون </h3>
                <p style="color: rgba(36, 36, 36, 0.6);font-size:16px">
                   هذه القائمة تحتوي على جميع الموظفون الذين تم اعتماد بياناتهم.
                </p>
            </div>

            <a href="{{route('employee.create')}}" class="submit-btn text-decoration-none rounded" style="padding-right:60px;padding-left:60px"> إضافة موظف </a>

        </div>



        <div class="table-responsive">
            <table class="table align-middle">

                <thead class="table-info">
                    <tr>
                        <th style="border-top-right-radius:15px"></th>
                        <th>الاسم</th>
                        <th> البريد الالكتروني </th>
                        <th> رقم الهاتف </th>
                        <th style="border-top-left-radius:15px">الإجراءات</th>

                    </tr>
                </thead>

                <tbody>


                    @forelse ($employees as $employee )

                        <tr>
                            <td></td>
                            <td> {{$employee->name}} </td>
                            <td> {{$employee->email}} </td>
                            <td> {{$employee->phone}} </td>
                            <td class="d-flex flex-wrap gap-1">

                                <div>
                                    <a href="{{route('employee.show' , $employee->id)}}" class="text-decoration-none mb-1 btn btn-outline-success " >  عرض التفاصيل  </a>
                                </div>

                                <div>
                                    <button  class="submit d-flex btn-delete btn btn-outline-danger" >
                                        حذف
                                    </button>

                                    <form  action="{{route('employee.destroy' , $employee->id)}}" method="post" style="display: none">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" name="delete_ids" value="{{ $employee->id }}">

                                    </form>
                                </div>

                            </td>

                        </tr>

                    @empty

                        <td colspan="4" class="fs-4 text-center" style="color: var(--primary-color)">  لا يوجد موظفين  مسجلين في النظام </td>

                    @endforelse


                </tbody>

            </table>
        </div>


    </section>


    {{$employees->withQueryString()->links()}}
</x-main-layout>

