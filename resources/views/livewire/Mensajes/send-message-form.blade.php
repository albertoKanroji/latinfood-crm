<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>Messages | Chats</b>
                </h4>
              
            </div>
         

            <div class="widget-content">
                
              <div class="container">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <!-- Lista de usuarios -->
            <ul class="list-group">
                @foreach ($users as $user)
                    <li class="list-group-item">{{ $user->name }}</li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-8">
            <!-- Chat -->
            <div class="card">
                <div class="card-body">
                    <div class="col-md-8">
            <!-- Chat -->
            <div class="card">
                <div class="card-header">
                    {{ $selectedUser->name }}
                </div>
                <div class="card-body">
                    <!-- Mostrar conversación -->
                    <ul class="list-unstyled">
                        @foreach ($messages as $message)
                            <li>
                                <strong>{{ $message->user->name }}:</strong> {{ $message->content }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

            </div>


        </div>


    </div>

   
</div>


<script>
    document.addEventListener('DOMContentLoaded', function(){

    });
</script>