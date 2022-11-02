@extends('layouts.tabulationMonitor')

@section('styles')

  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

@endsection
@section('content')


{{-- <div class="jumbotron">
    <h2 class="page-header text-center title text-black"><b>{{$categories->where('id',$categorySelected)->first()->name}}</b></h2>
</div> --}}


<!-- Main content -->
<section class="content">
    <div class="row d-flex justify-content-center">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="alert alert-danger alert-dismissible" id="alert" style="display:none;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <span class="message"></span>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                  <h3 class="text-center title text-black"><b>{{$categories->where('id',$categorySelected)->first()->name}}</b></h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div>
                        <button class="btn btn-primary" id="btnRefresh">Refresh table</button>
                        <button class="btn btn-primary d-none" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Refreshing...
                          </button>
                    </div>

                  <table id="example1" class="table table-bordered table-striped {{ Session::get('main-body') ? "table-dark" : "" }} disabled">
                    <thead>
                        <tr>
                        <th width="10" class="text-center">#</th>
                        <th class="text-center text-nowrap px-4">Participant Name</th>
                            @foreach ($judgeData as $judge)
                                <th class="text-center text-nowrap px-0 mx-0 py-2">{{$judge->name}}<br>{{$judge->username}}</th>
                            @endforeach
                        <th width="20" class="text-center text-nowrap">Total Average<br>100%</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach ($participants as $rowcount => $participant)
                        <tr >
                            <td width="10" class="text-center text-nowrap" data-participant-id="{{$participant->id}}"  data-row-count="{{$rowcount+1}}">
                                <b>
                                    @if ($participant->type == 1)
                                        Mr. {{$participant->number}}
                                    @elseif ($participant->type == 2)
                                        Ms. {{$participant->number}}
                                    @elseif ($participant->type == 3)
                                        Grp. {{$participant->number}}
                                    @elseif ($participant->type == 4)
                                        Team {{$participant->number}}
                                    @endif
                                </b>
                            </td>
                            <td class="text-nowrap">
                                <a href="" style="color: inherit;" data-toggle="modal" data-target="#participantPreview{{$participant->id}}">
                                    <u>
                                        <b>{{$participant->name}}</b>
                                    </u>
                                </a>
                            </td>

                            @php
                                $a = 'A';
                            @endphp

                            @foreach ($judgeData as $key => $judge)

                            @php
                                $scoreValue = '';
                                if (!empty($judgeAverage->where('judge_id', $judge->id )->where('participant_id', $participant->id )->first()->average)) {
                                    $scoreValue = $judgeAverage->where('judge_id', $judge->id )->where('participant_id', $participant->id )->first()->average;
                                }
                            @endphp

                            <td class="text-center" >
                                <div class="form-group has-validation d-flex justify-content-center">
                                    <div class="text-center">
                                        <div class="spinner-grow" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                    <input class="d-none text-center form-control @if($scoreValue) is-valid @endif}}" style="width: 8em"
                                        min="{{$tabulations->score_min}}"
                                        max="{{$tabulations->score_max}}"
                                        type="number" name="{{$a++ . ($rowcount+1)}}"
                                        data-judge-id="{{$judge->id}}"
                                        data-row-count="{{$rowcount+1}}"
                                        data-participant-id="{{$participant->id}}"
                                        value="{{$scoreValue}}"
                                        readonly/>

                                </div>
                            </td>
                            @endforeach
                            <td class="text-center">
                                <label class="text-primary TotalAverage{{$rowcount+1}}">
                                    0.00%
                                </label>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th width="10" class="text-center">#</th>
                            <th class="text-center text-nowrap px-4">Participant Name</th>
                                @foreach ($judgeData as $judge)
                                    <th class="text-center text-nowrap px-0 mx-0 py-2">{{$judge->name}}<br>{{$judge->username}}</th>
                                @endforeach
                            <th width="20" class="text-center text-nowrap">Total Average<br>100%</th>
                        </tr>
                    </tfoot>
                  </table>
                </div>
                <!-- /.card-body -->
            </div>
              <!-- /.card -->
        </div>
    </div>
</section>

{{-- PREVIEW PARTICIPANTS PROFILE --}}
@foreach ($participants as $rowcount => $participant)

<div class="modal fade" id="participantPreview{{$participant->id}}"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Profile</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr class="text-wrap text-center">
                            <td colspan="2" style="text-align: center; vertical-align: middle;">

                                @if ($participant->image->count() > 0)
                                    @foreach($participant->image as $key => $media)
                                        ​<picture>
                                            <img src="{{ $media->getUrl() }}" class="img-fluid img-thumbnail" alt="{{ $participant->name }}">
                                        </picture>
                                    @endforeach
                                @else
                                    <picture>
                                        <img src="{{ asset('assets/img/defaultuser.png') }}" class="img-fluid img-thumbnail" alt="{{ $participant->name }}">
                                    </picture>
                                @endif

                            </td>
                        </tr>

                        <tr>
                            <th>
                                Participant Number
                            </th>
                            <td>
                                @if ($participant->type == 1)
                                    Mr. {{$participant->number}}
                                @elseif ($participant->type == 2)
                                    Ms. {{$participant->number}}
                                @elseif ($participant->type == 3)
                                    Grp. {{$participant->number}}
                                @elseif ($participant->type == 4)
                                    Team {{$participant->number}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Participan Name
                            </th>
                            <td>
                                {{ $participant->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Others
                            </th>
                            <td>
                                {{ $participant->description }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endforeach





@endsection
@section('scripts')

<!-- jQuery -->
{{-- <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script> --}}
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "paging": false,"ordering": false, "searching": false
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

<script>

    $(document).ready(function () {
        var CategoryStatus = null;

        function refreshCompute(){
            var rowCount = '{{$participants->count()}}';
            for (let index = 1; index <= rowCount; index++) {

                var JudgeScore = 'input[name="A' + index + '"]';
                ComputeTotalScore(JudgeScore);

            }
        }

        $(window).on('load', function() {
            refreshCompute();
            //categoryLocked();

            function moveItem() {

            }

            setInterval(moveItem, 5000);

            setInterval(
                function()
                {
                    //categoryLocked();
                }, 5000);

        });

        function categoryLocked(){
            $.ajax({
                url: "",
                data: {
                    cat_id: "",
                    title_id: "{{$tabulations->id}}"
                },
                success: function (data) {

                    $.each(data, function (id, value) {
                        console.log(value);
                        CategoryStatus = value;
                    });


                }
            });

        }

        function criteriaLocked($this){

            var returnValue;
                $.ajax({
                    url: "",
                    data: {
                        cri_id: $($this).data('criteria-id')
                        },
                    success: function (data) {
                        $.each(data, function (id, value) {
                            //$(JudgeScore).last().val(value);
                            // console.log(value);
                            // returnValue = value;
                            JudgeSaveScores(value, $this);
                             //return returnValue;
                        });


                    },
                    errors: function (data) {
                            //console.log(data);
                        }
                });

                return returnValue;
        }

        function JudgeSaveScores($myStatus, $this){


            if ($myStatus == 'false') {

                //Save Judge Scores
                $.ajax({

                    // type: "POST",
                    //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

                    url: "",
                    data: {
                            //"_token": "{{ csrf_token() }}",
                            title_id: "{{$tabulations->id}}",
                            cat_id: "{{$categories->where('id',$categorySelected)->first()->id}}",
                            cri_id: $($this).data('judge-id'), //data-criteria-id
                            judge_id: "",
                            participant_id: $($this).data('participant-id'),
                            score_val: $($this).val()
                        },
                    success: function (data) {


                            setTimeout(
                            function()
                            {
                                //ComputeTotalScore(this);
                                ComputeTotalScore($this);

                            }, 1000);

                            //ComputeTotalScore(this);
                            $($this).blur();
                            $($this).removeClass('is-invalid');
                            $($this).addClass('is-valid');

                    },
                    errors: function (data) {
                        //$position.html('<option value="" selected>Please select</option>');
                            //console.log(data)

                    }

                });

            }else{

                alert('Unable to save score. The criteria is already locked!');
                $($this).removeClass('is-valid');
                $($this).addClass('is-invalid');
                $($this).last().removeClass('d-none')
                $($this).last().siblings().addClass('d-none')

                $.ajax({
                    url: "",
                    data: {
                            //"_token": "{{ csrf_token() }}",
                            title_id: "{{$tabulations->id}}",
                            cat_id: "{{$categories->where('id',$categorySelected)->first()->id}}",
                            cri_id: $($this).data('criteria-id'), //data-criteria-id
                            participant_id: $($this).data('participant-id')

                        },
                    success: function (data) {

                            $.each(data, function (id, value) {
                                   if (value) {
                                        $($this).last().val(value);
                                        ComputeTotalScore($this);
                                   }else{
                                       alert('poge');
                                   }
                            });

                    },
                    errors: function (error) {
                            console.log(error);

                    }
                });

            }

        }

        //alphabet increment
        function nextChar(cchar) {
                    return String.fromCharCode('A'.charCodeAt() + cchar);
        }

        function ComputeTotalScore($this){
                    //$($this).addClass('border border-danger');

                    //COMPUTATION BEGIN!!!

                    var maxScore = '{{$tabulations->score_max}}'
                    var rowCount = $($this).data('row-count');
                    var criteriaCount = '{{$judgeData->count()}}'
                    var ScoreData = parseFloat(0);
                    var TotalScore = parseFloat(0);
                    var CriteriaPercentage = parseFloat(0);
                    var JudgeScore;


                    for (let i = 1; i <= criteriaCount; i++) {

                        //reset
                        ScoreData = 0

                        JudgeScore = 'input[name="' + nextChar(i-1) + rowCount + '"]';

                        //get score
                        ScoreData = parseFloat($(JudgeScore).last().val());

                        $(JudgeScore).last().removeClass('is-invalid');
                        $(JudgeScore).last().addClass('is-valid');

                        //compute percentage
                        TotalScore = parseFloat(TotalScore) + parseFloat(ScoreData);


                        $(JudgeScore).last().removeClass('d-none')
                        $(JudgeScore).last().siblings().addClass('d-none')

                    }

                    //compute final output
                    TotalScore = parseFloat(TotalScore) / parseFloat(criteriaCount);

                    $('.TotalAverage' + rowCount).html(TotalScore.toFixed(2) + '%');
        }

        var oTable = $('#example1').DataTable();
        $("#myInput").on("keyup", function(e) {
            e.preventDefault();


            oTable.search( $(this).val()).draw();

        });

        $("#closeSearch").click(function (e) {
            e.preventDefault();
            $("#myInput").val('');
            oTable.search( $(this).val()).draw();


        });

        //responsive mode
        $('[tabindex="0"]').click(function (e) {
            //alert($(this).data('participant-id'));
            var ParticipantID = $(this).data('participant-id');
            //var Scores = ["90", "80"];
            var criteriaCount = '{{$judgeData->count()}}';

            var rowCount = $(this).data('row-count');
            var $this = $(this);

            var JudgeScore;

            setTimeout(
                function()
                {

                        for (let i = 1; i <= criteriaCount; i++) {

                            JudgeScore = 'input[name="' + nextChar(i-1) + rowCount + '"]';

                            $.ajax({
                                url: "{{ url('admin/monitors/getscores/')}}",
                                data: {
                                        //"_token": "{{ csrf_token() }}",
                                        title_id: "{{$tabulations->id}}",
                                        cat_id: "{{$categories->where('id',$categorySelected)->first()->id}}",
                                        cri_id: $(JudgeScore).data('criteria-id'), //data-criteria-id
                                        judge_id: "",
                                        participant_id: ParticipantID
                                    },
                                success: function (data) {
                                        JudgeScore = 'input[name="' + nextChar(i-1) + rowCount + '"]';
                                        $.each(data, function (id, value) {
                                            $(JudgeScore).last().val(value);
                                        });
                                        ComputeTotalScore(JudgeScore);

                                },
                                errors: function (data) {
                                        //console.log(data)

                                }
                            });
                            //COMPUTATION BEGIN!!!


                        }

                }, 100);

        });

        function refreshData(){

            rowCount = '{{$participants->count()}}';
            criteriaCount = '{{$judgeData->count()}}';

            for (let x = 1; x <= rowCount; x++) {
                for (let i = 1; i <= criteriaCount; i++) {

                        JudgeScore = 'input[name="' + nextChar(i-1) + x + '"]';
                        //alert(JudgeScore + ' :: ' + $(JudgeScore).val());

                        $.ajax({
                            url: "{{ url('admin/monitors/getaverage/')}}",
                            data: {
                                    //"_token": "{{ csrf_token() }}",
                                    title_id: "{{$tabulations->id}}",
                                    cat_id: "{{$categories->where('id',$categorySelected)->first()->id}}",
                                    judge_id: $(JudgeScore).data('judge-id'), //data-criteria-id
                                    participant_id: $(JudgeScore).data('participant-id')
                                },
                            success: function (data) {
                                    JudgeScore = 'input[name="' + nextChar(i-1) + x + '"]';
                                    $(JudgeScore).last().val('');
                                    $(JudgeScore).last().removeClass('is-valid');

                                    $.each(data, function (id, value) {
                                        $(JudgeScore).last().val(value);

                                    });
                                    ComputeTotalScore(JudgeScore);

                                    if (rowCount == x) {
                                        $('#btnRefresh').removeClass('d-none');
                                        $('#btnRefresh').siblings().addClass('d-none');
                                    }

                            },
                            errors: function (data) {
                                    //console.log(data)

                            }
                        });
                        //COMPUTATION BEGIN!!!
                }


            }




        }

        $('#btnRefresh').click(function (e) {
            e.preventDefault();
            refreshData();
            $(this).addClass('d-none');
            $(this).siblings().removeClass('d-none');


        });


    });

</script>
@endsection
