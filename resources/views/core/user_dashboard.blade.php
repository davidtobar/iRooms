<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="{{$login->favicon}}" />
  @yield("metas")

  <title>@yield("pageTitle") :: {{$login->app_name}}</title>

  <!-- Styles -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/AdminLTE.css') }}" rel="stylesheet">
  <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
  <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/materialdesignicons.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bootstrap-timepicker.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ url('css/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ url('css/spectrum.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.4/sweetalert2.min.css" >
  <link href="https://fonts.googleapis.com/css?family=Heebo:300,400,500,700,800,900" rel="stylesheet">
  @yield("styles")

</head>
<body class="hold-transition skin-blue sidebar-mini {{ Session::get('sidebarState') }}">
  <!-- <?php 
    // var_dump (date ('Ymd H: i: s'));
  ?>-->
  <div class="wrapper">
    @include("components.navigation.header")
    @include("components.navigation.user_sidebar")

    <div class="content-wrapper section-w">
      @yield("content")
    </div>
  </div>
  
  @section('modal')
  @show
  
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/adminlte.min.js') }}"></script>
  <script src="{{ asset('js/jquery-ui.js') }}"></script>
  <script src="{{ asset('js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ url('js/bootstrap-datepicker.min.js') }}"></script>

  <script src="{{ url('js/app-dashboard.js') }}"></script>
  <script src="{{ url('js/app-custom-dashboard.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.17.0/axios.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.4/sweetalert2.min.js"></script>
  <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

  <script src="{{ url('js/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
  <script src="{{ url('js/interactjs/interact.min.js') }}"></script>
  <script src="{{ url('js/moment/moment-with-locales.min.js') }}"></script>

  <script type="text/javascript">
    var baseURL = {!! json_encode(url('/')) !!};
  </script>
  <script src="{{ url('js/user-calendar.js') }}"></script>
  <!-- <script src="{{ url('js/calendar_month.js') }}"></script> -->
  <script src="{{ url('js/spectrum.js') }}"></script>

  <script>
   $('#sidebarToggle').on('click', function(e) {
       $.ajax({
           type: "post",
           url: "{{ url('savestate') }}",
           headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });
   });
  </script>

  @yield("scripts")

  <script>
    $(function () {

      /*Search*/
      var src = "{{ route('searchajax') }}";
      $("#search_text").autocomplete({
          source: function(request, response) {
            $.ajax({
                type: "post",
                url: src,
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                data: {
                  term : request.term
                },
                success: function(data) {
                  response(data);

                  $(".ui-menu .ui-menu-item:first-child").addClass("selected");
                  
                  $(".ui-menu .ui-menu-item-wrapper").on("click", function(e){
                    $(".ui-menu .ui-menu-item:first-child").removeClass("selected");
                    $(e.target).parent('li').addClass('selected');
                  });

                }
            });
          },
          // focus: function (event, ui) {
          //     $("#search_text").val(ui.item.label);
          //     $("#project-i").val(ui.item.id);
          //     return false;
          // },
          select: function (event, ui) {
              $("#search_text").val(ui.item.value);                   

              var meet_id = ui.item.id;
              var meet_start = ui.item.start_date;

              $.ajax({
                  type: "post",
                  url: "{{ route('searchdata') }}",
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  data: {
                    id: meet_id,
                    start: meet_start
                  },
                  success: function(data) {

                    if (data.length != 0) {
                      $("#searchResult").modal();
                      $("#searchResults #meet-name").text(data[0].meeting_name);
                      $("#searchResults #meet-autor").text(data[0].user_name);
                      $("#searchResults #meet-room").text(data[0].room_name);
                      $("#searchResults #meet-from").text(moment(data[0].start_date).format("ddd, DD MMM")+' / '+ moment(data[0].start_time, "HH:mm").format("hh:mm A"));
                      $("#searchResults #meet-to").text(moment(data[0].end_date).format("ddd, DD MMM")+' / '+ moment(data[0].end_time, "HH:mm").format("hh:mm A"));
                      $("#searchResults #meet-description").text(data[0].description);
                    }

                    $('#searchResult').on('hidden.bs.modal', function () {
                        $("#searchResults .search-option").text("");
                    });

                  }
              });

              return false;
          },
          minLength: 1,
         
      }).data("ui-autocomplete")._renderItem = function (ul, item) {
          if(item.start_date == undefined){
            return $("<li>")
            .data("ui-autocomplete-item", item)
            .append("<a>" + item.value + "</a>")
              .appendTo(ul);
          }else{
            return $("<li>")
            .data("ui-autocomplete-item", item)
            .append("<a data-at="+item.id+"> " + item.value + "<span>" + moment(item.start_date).format("DD MMM YYYY") + "</span></a>")
              .appendTo(ul);
          }
      };

      $("#search_text").keyup(function(e){
        if ($(this).val() == "") {
          $(".ui-menu .ui-menu-item").removeClass("selected");
        }

      });

      $("#search_text").bind("enterKey",function(e){

           var meet_id = $(".ui-menu .ui-menu-item.selected a").attr("data-at");
           var meet_start = $(".ui-menu .ui-menu-item.selected a span").text();

           var meet_startf = moment(meet_start).format("YYYY-MM-DD");

           $.ajax({
               type: "post",
               url: "{{ route('searchdata') }}",
               headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               data: {
                 id: meet_id,
                 start: meet_startf
               },
               success: function(data) {

                 if (data.length != 0) {
                   $("#searchResult").modal();
                   $("#searchResults #meet-name").text(data[0].meeting_name);
                   $("#searchResults #meet-autor").text(data[0].user_name);
                   $("#searchResults #meet-room").text(data[0].room_name);
                   $("#searchResults #meet-from").text(moment(data[0].start_date).format("ddd, DD MMM")+' / '+ moment(data[0].start_time, "HH:mm").format("hh:mm A"));
                   $("#searchResults #meet-to").text(moment(data[0].end_date).format("ddd, DD MMM")+' / '+ moment(data[0].end_time, "HH:mm").format("hh:mm A"));
                   $("#searchResults #meet-description").text(data[0].description);
                 }

                 $('#searchResult').on('hidden.bs.modal', function () {
                     $("#searchResults .search-option").text("");
                 });

               }
           });

         });
      
      $("#search_text").keyup(function(e){
           if(e.keyCode == 13)
           {
              $(this).trigger("enterKey");
           }
      });

    });
  </script>
</body>
</html>