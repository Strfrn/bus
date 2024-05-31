@extends('layouts.app')
@section('title', 'Cari Kursi')
@section('styles')
<style>
    a:hover {
        text-decoration: none;
    }
    .kursi {
        box-sizing: border-box;
        border: 2px solid #858796;
        width: 100%;
        height: 120px;
        display: flex;
    }
</style>
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-12" style="margin-top: -15px">
        <a href="javascript:window.history.back();" class="text-white btn"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
        <div class="row mt-2">
            @for ($i = 1; $i <= $transportasi->jumlah; $i++)
                @php
                    $seatNumber = 'K' . $i;
                    $isBooked = $transportasi->isSeatBooked($data['id'], $seatNumber, $data['waktu']);
                @endphp
                @if (!$isBooked)
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                        <a href="#" data-toggle="modal" data-target="#confirmModal" data-seat="{{ $seatNumber }}">
                            <div class="kursi bg-white">
                                <div class="font-weight-bold text-primary m-auto" style="font-size: 26px;">{{ $seatNumber }}</div>
                            </div>
                        </a>
                    </div>
                @else
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                        <div class="kursi" style="background: #858796">
                            <div class="font-weight-bold text-white m-auto" style="font-size: 26px;">{{ $seatNumber }}</div>
                        </div>
                    </div>
                @endif
            @endfor
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Pemesanan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin memesan kursi <span id="selectedSeat"></span>?
            </div>
            <div class="modal-footer">
                <form action="" method="GET" id="confirmForm">
                    @csrf
                    <input type="hidden" name="kursi" id="kursi" value="">
                    <input type="hidden" name="data" id="data" value="{{ Crypt::encrypt($data) }}">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Pesan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('#confirmModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var seat = button.data('seat');
        var modal = $(this);
        modal.find('#selectedSeat').text(seat);
        modal.find('#kursi').val(seat);

        var data = @json(Crypt::encrypt($data));
        var formAction = "{{ url('pesan') }}/" + seat + "/" + data;
        modal.find('#confirmForm').attr('action', formAction);
    });

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
    }
</script>
@endsection
