<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Ticket</title>
        <script  src="https://code.jquery.com/jquery-1.12.4.min.js"  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="  crossorigin="anonymous"></script>

        <style>
            .tickets{
                font-family: Arial;
                font-size: 13px;
                width: 7cm;
                padding: 5px;

            }
            .tickets p{
                margin: 4px;
                padding: 1px;

            }
            .tkpie{
                font-family: Arial;
                font-size: 8px;
                font-weight: bold;
            }

            .negrita{
                font-weight: bold;
            }
            .centrar{
                text-align: center;
            }
            .justificar{
                text-align: justify;
            }
        </style>
        <script>
            $(document).ready(function(){
                setTimeout(function(){window.print();}, 1000);
            });

        </script>
    </head>
    <body>
        <div class="tickets">
            <p><img src='{{$logo}}' alt='' border="0"/>{{ $empresa}}</p>
            <br/>
            <p>NO OLVIDE:</p>
            <br/>
            <p>Sr./Sra.</p>
            <p class="negrita"><?php echo $paciente->nom_ape;?></p>
            <br/>
            @foreach ($citas as $cita)
                <p class="negrita"> {{ getFechaHoraDia($cita->fecha.' '.$cita->hora) }}</p>
            @endforeach
            <br/>
            <p class="justificar">Le agradecemos nos comunique con
                antelación cualquier modificación que
                desee, GRACIAS.</p>
            <p class="negrita" style="text-align: center;">Tf.: <?php echo $telefono;?></p>
                        <p style="text-align: center;">----</p>
            </div>
    </body>
</html>
