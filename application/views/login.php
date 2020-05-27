<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Page Login</title>
	<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
	<style>
		@import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');

		.font-family-karla {
			font-family: karla;
		}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body class="bg-white font-family-karla h-screen">

	<div class="w-full flex flex-wrap">

		<!-- Login Section -->
		<div class="w-full md:w-1/2 flex flex-col">

			<div id="alert"></div>

			<div class="flex justify-center md:justify-start pt-12 md:pl-12 md:-mb-24">
				<a href="#" class="bg-black text-white font-bold text-xl p-4">Logo</a>
			</div>

			<div class="flex flex-col justify-center md:justify-start my-auto pt-8 md:pt-0 px-8 md:px-24 lg:px-32">
				<p class="text-center text-3xl">Welcome.</p>
				<form class="flex flex-col pt-3 md:pt-8" onsubmit="event.preventDefault();">
					<div class="flex flex-col pt-4">
						<label for="email" class="text-lg">Email</label>
						<input type="email" id="email" placeholder="your@email.com" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline" id="email">
					</div>

					<div class="flex flex-col pt-4">
						<label for="password" class="text-lg">Password</label>
						<input type="password" id="password" placeholder="Password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline" id="password">
					</div>

					<button type="submit" class="bg-black text-white font-bold text-lg hover:bg-gray-700 p-2 mt-8" id="login">Log In</button>
				</form>
				<div class="text-center pt-12 pb-12">
					<p>Don't have an account? <a href="register.html" class="underline font-semibold">Register here.</a></p>
				</div>
			</div>

		</div>

		<!-- Image Section -->
		<div class="w-1/2 shadow-2xl">
			<img class="object-cover w-full h-screen hidden md:block" src="https://source.unsplash.com/IXUM4cJynP0">
		</div>
	</div>

	<script>
		$('#login').on('click',login);
		function login(){
			var url	= '<?=base_url()?>v1/auth/login'; //get url
			var email = $('#email').val()
			var password = $('#password').val()

			$.ajax({
				url: url,
				method: 'post',
				dataType: 'json',
				data: {
					email: email,
					password: password,
				},
				success: (res) =>{
					switch(res.type){
						case 'success':

							window.localStorage.setItem('auth',res.token)
							window.location.href = 'admin/dashboard'

						break;
					}
				},
				error: (err) => {
					try{
						err = JSON.parse(err.responseText)
						switch(err.type){
							case 'error validation':

								$('#alert').html(`<div class="bg-red-100 border-t border-b border-red-500 text-red-700 px-4 py-3" role="alert">
			  <p class="font-bold">Informational message</p>
			  <p class="text-sm">${err.response}</p>
			</div>`);

								break;

						}
					}catch(er){
						console.log('Error Http Request')
						// console.log(er, err)
					}
				}
			})
		}
	</script>
</body>
</html>