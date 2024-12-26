<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Get Rates</title>
  </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <main class="mt-6">
                        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                            <form action="{{ route('rates.store') }}" method="POST" >
                                @csrf
                                <input name="accessToken" hidden value="{{(!is_null($accessToken))?$accessToken:''}}">
                                <input name="tokenExp" hidden value="{{(!is_null($tokenExp))?$tokenExp:''}}">
                                Vendor Id: <input name="vendorId" value="1901539643"/> <br>
                                Origin City: <input name="originCity" value="KEY LARGO"/> <br>
                                Origin State: <input name="originState" value="FL"/> <br>
                                Origin Zipcode: <input name="originZipcode" value="33037"/> <br>
                                Origin Country: <input name="originCountry" value="US"/> <br>
                                Destination City: <input name="destinationCity" value="LOS ANGELES"/> <br>
                                Destination State: <input name="destinationState" value="CA"/> <br>
                                Destination Zipcode: <input name="destinationZipcode" value="90001"/> <br>
                                Destination Country: <input name="destinationCountry" value="US"/> <br>
                                UOM: <input name="UOM" value="US"/> <br>
                                Freight Info: <br>
                                qty: <input name="qty" value="1"/> <br>
                                weight: <input name="weight" value="100"/> <br>
                                weightType: <input name="weightType" value="each"/> <br>
                                length: <input name="length" value="40"/> <br>
                                width: <input name="width" value="40"/> <br>
                                height: <input name="height" value="40"/> <br>
                                class: <input name="class" value="100"/> <br>
                                hazmat: <input name="hazmat" value="0"/> <br>
                                commodity: <input name="commodity" value=""/> <br>
                                dimType: <input name="dimType" value="PLT"/> <br>
                                stack: <input name="stack" value="false"/> <br>
                                <input type="submit" /> 
                            </form>
                        </div>

                        @if((isset($error) && !is_null($error) && $error!=""))   
                        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                            The server returned the error "{{$error}}". <br>
                        </div>
                        @endif  
                        
                        @if(isset($data) && !is_null($data))
                        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                        Final output:
                                "{{json_encode($data)}}"
                        </div>
                        @endif 
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
