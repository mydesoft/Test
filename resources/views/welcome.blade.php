<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Backend Developer Test</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

         <script src="https://cdn.tailwindcss.com"></script>

    </head>
    <body class="antialiased">
        <div class=" justify-center min-h-screen bg-gray-100 dark:bg-gray-900 items-center py-4 sm:pt-0">
           
                    <a href="/register" class="ml-4 text-sm text-gray-700">

                       <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div>
                        <div class="p-6">
                            <div class="flex items-center">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                <div class="ml-4 text-lg leading-7 font-semibold"><a href="https://laravel.com/docs" class="underline text-gray-900 dark:text-white">Registration</a></div>
                            </div>

                            <div class="ml-12">
                               <form method="POST" action="/api/v1/register">
                                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                   <div class="w-full mb-3 px-3 md:mb-5">
                                   <label class="font-lato2 form-text text-base">What is your name?</label>
                                    <input type="text" name="name" class="mt-2 appearance-none block w-full border border-gray-200 rounded py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Ex. Shola Ojo" value="{{old('name')}}" required>
                                 </div>

                                 <div class="w-full mb-3 px-3 md:mb-5">
                                   <label class="font-lato2 form-text text-base">What is your email?</label>
                                    <input type="email" name="email" class="mt-2 appearance-none block w-full border border-gray-200 rounded py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Ex. mide@test.com" value="{{old('email')}}" required>
                                 </div>

                                 <div class="w-full mb-3 px-3 md:mb-5">
                                   <label class="font-lato2 form-text text-base">Password</label>
                                    <input type="password" name="password" class="mt-2 appearance-none block w-full border border-gray-200 rounded py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                                 </div>

                                  <div class="w-full mb-3 px-3 md:mb-5">
                                   <label class="font-lato2 form-text text-base">Password Confirmation</label>
                                    <input type="password" name="password_confirmation" class="mt-2 appearance-none block w-full border border-gray-200 rounded py-2 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                                 </div>

                                  <div class="mt-7 mb-3 px-3 md:mb-5">
                                    <button class="text-white bg-green-600 rounded h-9 w-full">Submit</button>
                                  </div>
                               </form>
                                </div>
                            </div>
                        </div>
                </div>   
                </div>

        </div>
    </body>
</html>
