@if(session('success') || session('error') || session('warning') || session('info'))
<style>
.swal-toast-custom {
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
    padding: 12px 20px !important;
}
.swal-toast-success { border-left: 4px solid #22c55e !important; }
.swal-toast-error   { border-left: 4px solid #ef4444 !important; }
.swal-toast-warning { border-left: 4px solid #f59e0b !important; }
.swal-toast-info    { border-left: 4px solid #3b82f6 !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: @json(session('success')),
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        customClass: { popup: 'swal-toast-custom swal-toast-success' }
    });
    @endif

    @if(session('error'))
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: @json(session('error')),
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        customClass: { popup: 'swal-toast-custom swal-toast-error' }
    });
    @endif

    @if(session('warning'))
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'warning',
        title: @json(session('warning')),
        showConfirmButton: false,
        timer: 4500,
        timerProgressBar: true,
        customClass: { popup: 'swal-toast-custom swal-toast-warning' }
    });
    @endif

    @if(session('info'))
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'info',
        title: @json(session('info')),
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        customClass: { popup: 'swal-toast-custom swal-toast-info' }
    });
    @endif
});
</script>
@endif
