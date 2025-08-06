<x-main-layout title="مؤسسة سبيلي الخيرية">

    @push('styles')

        <style>
            .notification div{
                background-color: rgba(248, 250, 250, 1);
                border:1px solid rgba(230, 244, 244, 1);
                margin-bottom: 8px
            }
        </style>


    @endpush

    <section class="mt-1">

        <x-alert name="success" />
        <x-alert name="danger" />



        {{-- section header component --}}
        <div>

            <h3 class="mb-5"> الإشعارات </h3>


        </div>

        <div class="notification">

            @forelse ($notifications as $notification)

                <div class="d-flex flex-warp justify-content-between p-2 rounded">
                    <span> {{$notification->data['message']}} </span>
                    <span style="color: rgba(36, 36, 36, 0.6)"> {{$notification->created_at->diffForHumans()}} </span>
                </div>

            @empty

                <div class="p-3 fs-6 fw-semibold  rounded w-100 text-white" style="background-color:var(--primary-color);">
                    عذرًا، لا توجد أي إشعارات مرسلة
                </div>

            @endforelse


        </div>





    </section>




</x-main-layout>
