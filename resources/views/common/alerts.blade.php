@if (session()->has('message'))                        
         <script>
           toastr.success( "{{ @session('message') }}", "Info!!");                               
         </script>                           
@endif
@if (session()->has('msg-error'))                        
         <script>
           toastr.error("{{ @session('msg-error') }}", "Error!!");                               
         </script>                           
@endif
@if (session()->has('msg-ok'))                        
         <script>
           toastr.success("{{ @session('msg-ok') }}", "Perfecto!!");                               
         </script>                           
@endif
@if (session()->has('msg-ops'))                        
         <script>
           toastr.error("{{ @session('msg-ops') }}", "Atención!!");                               
         </script>                           
@endif