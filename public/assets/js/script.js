//this code to show image in input when create any thing need image
document.querySelectorAll('.hidden-file-style').forEach((input, index) => {
    input.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file) return;

        const wrapper = document.querySelectorAll('.custom-file-upload')[index] || input.closest('label');
        if (!wrapper) return;

        const fileType = file.type;

        const reader = new FileReader();
        reader.onload = function (e) {
            // أولاً، نحذف أي وسائط قديمة
            wrapper.querySelectorAll('img, video, audio').forEach(el => el.remove());

            if (fileType.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = "40px"; // أو "100%" أو أي قيمة مناسبة
                wrapper.appendChild(img);

            } else if (fileType.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = e.target.result;
                video.controls = true;
                video.style.width = "120px"; // أو "100%" أو أي قيمة مناسبة
                wrapper.appendChild(video);

            } else if (fileType.startsWith('audio/')) {
                const audio = document.createElement('audio');
                audio.src = e.target.result;
                audio.controls = true;
                audio.style.width = "80px"; // أو "100%" أو أي قيمة مناسبة
                wrapper.appendChild(audio);
            }
        };

        reader.readAsDataURL(file);
    });
});




// for delete button
$('.btn-delete').click(function(e) {
    let form = $(this).next();
    Swal.fire({
        title: 'هل أنت متأكد أنك تريد الحذف؟',
        text: "⚠️ هذا الإجراء لا يمكن التراجع عنه، وسيتم حذف العنصر بشكل نهائي من النظام.",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'إلغاء',
        confirmButtonColor: 'rgba(246, 92, 92, 1)',
        cancelButtonColor: 'rgba(1, 143, 145, 1)',
        confirmButtonText: 'حذف!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
});
