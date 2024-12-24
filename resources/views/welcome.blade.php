<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Get Rates</title>
    </head>
    <body>
        <div>
            <main class="mt-6">
                <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                    <form action="{{ route('users.store') }}" method="POST" >
                        @csrf
                        User Name: <input name="username" value="testDemo"/> 
                        Password: <input name="password" value="1234"/> 
                        <input type="submit" value="Get Token"/> 
                        </select>
                    </form>
                </div>
            </main>
        </div>
    </body>
</html>
