@if (session()->has('message'))                        
         <script>
           toastr.success( "{{ @session('message') }}", "info");                               
         </script>                           
@endif
@if (session()->has('msg-error'))                        
         <script>
           toastr.error("{{ @session('msg-error') }}", "info");                               
         </script>                           
@endif
@if (session()->has('msg-ok'))                        
         <script>
           toastr.success("{{ @session('msg-ok') }}", "info");                               
         </script>                           
@endif
@if (session()->has('msg-ops'))                        
         <script>
           toastr.error("{{ @session('msg-ops') }}", "info");                               
         </script>                           
@endif