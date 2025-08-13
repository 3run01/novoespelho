<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <title>404 Not Found</title>
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
       <script src="https://cdn.tailwindcss.com"></script>
       <style>
           .animate-float {
               animation: float 4s ease-in-out infinite;
           }

           @keyframes float {
               0% {
                   transform: translateY(0px);
               }
               50% {
                   transform: translateY(-15px);
               }
               100% {
                   transform: translateY(0px);
               }
           }
           body {
               background-color: #f8fafc;
               color: #333;
               font-family: Arial, sans-serif;
               text-align: center;
           }
       </style>
       <meta http-equiv="refresh" content="5;url='/'" />
   </head>
   <body class="bg-white min-h-screen flex items-center justify-center p-4">
       <div class="max-w-2xl w-full p-8">
           <div class="text-center">
               <img src="{{ asset('logo.png') }}" alt="Logo do Ministério Público do Amapá" class="w-20 h-20 mx-auto mb-4">
               <div class="mb-12">
                   <svg class="w-48 h-48 mx-auto" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                       <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#334155" font-size="72" font-weight="bold" font-family="system-ui">
                           404
                       </text>
                   </svg>
               </div>
               <h1 class="text-3xl font-bold text-slate-900 mb-4">Página não encontrada</h1>
               <p class="text-slate-600 mb-8 text-lg">Desculpe, mas a página que você está procurando não existe ou foi removida.</p>
               <div class="flex flex-col sm:flex-row gap-4 justify-center">
                   <a href="/" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg transition-all duration-200 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                       <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                       </svg>
                       Voltar para o Inicio
                   </a>
                   
               </div>
           </div>
       </div>
   </body>
</html>
