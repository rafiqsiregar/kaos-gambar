<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pages</title>
    <meta name="author" content="David Grzyb">
    <meta name="description" content="">

    <!-- Tailwind -->
    <link href="https://unpkg.com/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <!--Regular Datatables CSS-->
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <!--Responsive Extension Datatables CSS-->
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');
        .font-family-karla { font-family: karla; }
        .bg-sidebar { background: #3d68ff; }
        .cta-btn { color: #3d68ff; }
        .upgrade-btn { background: #1947ee; }
        .upgrade-btn:hover { background: #0038fd; }
        .active-nav-link { background: #1947ee; }
        .nav-item:hover { background: #1947ee; }
        .account-link:hover { background: #3d68ff; }
        /*Overrides for Tailwind CSS */
        
        /*Form fields*/
        .dataTables_wrapper select,
        .dataTables_wrapper .dataTables_filter input {
            color: #4a5568;             /*text-gray-700*/
            padding-left: 1rem;         /*pl-4*/
            padding-right: 1rem;        /*pl-4*/
            padding-top: .5rem;         /*pl-2*/
            padding-bottom: .5rem;      /*pl-2*/
            line-height: 1.25;          /*leading-tight*/
            border-width: 2px;          /*border-2*/
            border-radius: .25rem;      
            border-color: #edf2f7;      /*border-gray-200*/
            background-color: #edf2f7;  /*bg-gray-200*/
        }

        /*Row Hover*/
        table.dataTable.hover tbody tr:hover, table.dataTable.display tbody tr:hover {
            background-color: #ebf4ff;  /*bg-indigo-100*/
        }
        
        /*Pagination Buttons*/
        .dataTables_wrapper .dataTables_paginate .paginate_button       {
            font-weight: 700;               /*font-bold*/
            border-radius: .25rem;          /*rounded*/
            border: 1px solid transparent;  /*border border-transparent*/
        }
        
        /*Pagination Buttons - Current selected */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current   {
            color: #fff !important;             /*text-white*/
            box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);    /*shadow*/
            font-weight: 700;                   /*font-bold*/
            border-radius: .25rem;              /*rounded*/
            background: #667eea !important;     /*bg-indigo-500*/
            border: 1px solid transparent;      /*border border-transparent*/
        }

        /*Pagination Buttons - Hover */
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover     {
            color: #fff !important;             /*text-white*/
            box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);     /*shadow*/
            font-weight: 700;                   /*font-bold*/
            border-radius: .25rem;              /*rounded*/
            background: #667eea !important;     /*bg-indigo-500*/
            border: 1px solid transparent;      /*border border-transparent*/
        }
        
        /*Add padding to bottom border */
        table.dataTable.no-footer {
            border-bottom: 1px solid #e2e8f0;   /*border-b-1 border-gray-300*/
            margin-top: 0.75em;
            margin-bottom: 0.75em;
        }
        
        /*Change colour of responsive icon*/
        table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child:before, table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child:before {
            background-color: #667eea !important; /*bg-indigo-500*/
        }
    </style>
</head>
<body class="bg-gray-100 font-family-karla flex">

    <aside class="relative bg-sidebar h-screen w-64 hidden sm:block shadow-xl">
        <div class="p-6">
            <a href="index.html" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>
            <button onclick="window.location.href = '/admin/product' " class="w-full bg-white cta-btn font-semibold py-2 mt-5 rounded-br-lg rounded-bl-lg rounded-tr-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
                <i class="fas fa-plus mr-3"></i> Add Product
            </button>
        </div>
        <nav class="text-white text-base font-semibold pt-3">
            <a href="dashboard" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="product" class="flex items-center active-nav-link text-white py-4 pl-6 nav-item">
                <i class="fas fa-box mr-3"></i>
                Product
            </a>
            <a href="purchase" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-store mr-3"></i>
                Purchase
            </a>
            <a href="income" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-dollar-sign mr-3"></i>
                Income
            </a>
            <a href="account" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-user mr-3"></i>
                Account
            </a>
            <a href="logout" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-sign-out-alt mr-3"></i>
                Logout
            </a>
        </nav>
    </aside>

    <div class="w-full flex flex-col h-screen overflow-y-hidden">
        <!-- Desktop Header -->
        <header class="w-full flex items-center bg-white py-2 px-6 hidden sm:flex">
            <div class="w-1/2"></div>
            <div x-data="{ isOpen: false }" class="relative w-1/2 flex justify-end">
                <button @click="isOpen = !isOpen" class="realtive z-10 w-12 h-12 rounded-full overflow-hidden border-4 border-gray-400 hover:border-gray-300 focus:border-gray-300 focus:outline-none">
                    <img src="https://source.unsplash.com/uJ8LNVCBjFQ/400x400">
                </button>
                <button x-show="isOpen" @click="isOpen = false" class="h-full w-full fixed inset-0 cursor-default"></button>
                <div x-show="isOpen" class="absolute w-32 bg-white rounded-lg shadow-lg py-2 mt-16">
                    <a href="#" class="block px-4 py-2 account-link hover:text-white">Account</a>
                    <a href="#" class="block px-4 py-2 account-link hover:text-white">Sign Out</a>
                </div>
            </div>
        </header>

        <!-- Mobile Header & Nav -->
        <header x-data="{ isOpen: false }" class="w-full bg-sidebar py-5 px-6 sm:hidden">
            <div class="flex items-center justify-between">
                <a href="index.html" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>
                <button @click="isOpen = !isOpen" class="text-white text-3xl focus:outline-none">
                    <i x-show="!isOpen" class="fas fa-bars"></i>
                    <i x-show="isOpen" class="fas fa-times"></i>
                </button>
            </div>

            <!-- Dropdown Nav -->
            <nav :class="isOpen ? 'flex': 'hidden'" class="flex flex-col pt-4">
                <a href="dashboard" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="product" class="flex items-center active-nav-link text-white py-4 pl-6 nav-item">
                    <i class="fas fa-box mr-3"></i>
                    Product
                </a>
                <a href="purchase" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                    <i class="fas fa-store mr-3"></i>
                    Purchase
                </a>
                <a href="income" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                    <i class="fas fa-dollar-sign mr-3"></i>
                    Income
                </a>
                <a href="account" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                    <i class="fas fa-user mr-3"></i>
                    Account
                </a>
                <a href="logout" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </a>
            </nav>
            <!-- <button class="w-full bg-white cta-btn font-semibold py-2 mt-5 rounded-br-lg rounded-bl-lg rounded-tr-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
                <i class="fas fa-plus mr-3"></i> New Report
            </button> -->
        </header>

        <div class="w-full overflow-x-hidden border-t flex flex-col">
            <main class="w-full flex-grow p-6">
                <h1 class="text-3xl text-black pb-6">Product</h1>

                <div class="w-full mt-12 shadow-lg">
                    <p class="text-xl pb-3 flex items-center p-5 bg-white">
                        <i class="fas fa-edit mr-3"></i> Edit Selected Product
                    </p>
                    <div class="bg-white overflow-auto p-5">
                        <div class="container">
                            <div class="grid lg:grid-cols-2 sm:grid-cols-1 gap-4">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                        Product Name
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Name" id="name">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                                        Price
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="number" placeholder="Rp." id="price">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="photo">
                                        Photo
                                    </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="file" placeholder="Pick Image" accept="image/*" id="photo">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                        Description
                                    </label>
                                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Description" id="description"></textarea>
                                </div>
                                <div class="mb-4">
                                    <img src="" alt="" id="image-preview" class="w-full">
                                </div>
                                <div class="mb-4">
                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="editProduct"><i class="fas fa-plus"></i> Edit Product</button>
                                </div>
                            </div>
                            <a href="<?=base_url()?>admin/product" class="bg-gray-300 text-black-400 py-2 px-4 rounded"><i class="fas fa-chevron-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </main>

                <footer class="w-full bg-white text-right p-4">
                    <!-- Built by <a target="_blank" href="https://davidgrzyb.com" class="underline">David Grzyb</a>. -->
                </footer>
            </div>

        </div>

        <!-- AlpineJS -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
        <!-- Font Awesome -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
        <!-- ChartJS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
        <!-- jQuery -->
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
            
        <!--Datatables -->
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script>
            var token = window.localStorage.getItem('auth');

            $('#photo').on('input', function(){
                function getBase64(file) {
                   var reader = new FileReader();
                   reader.readAsDataURL(file);
                   reader.onload = function () {
                     $('#image-preview').attr('src',reader.result);
                   };
                   reader.onerror = function (error) {
                     console.log('Error: ', error);
                   };
                }

                var file = $(this)[0].files[0];
                getBase64(file); // prints the base64 string
            })

            $('#editProduct').click(function(){

                var name = $('#name').val();
                var price = $('#price').val();
                var photo = $('#image-preview').attr('src');
                var description = $('#description').val();

                var urlData = window.location.href.split('/');
                var urlID = urlData[urlData.length -1];
                var urls = '<?=base_url()?>v1/product/edit';

                $.ajax({
                    url: urls,
                    data: {
                        token: token,
                        name: name,
                        price: price,
                        photo: photo,
                        description:  description,
                        id: urlID
                    },
                    dataType: 'json',
                    method: 'post',
                    success: (res)=>{
                        // console.log(res)
                        if(res.type == 'success'){
                            window.location.reload();
                        }
                    },
                    error: (err)=>{
                        try{
                            err = JSON.parse(err.responseText)
                            console.log(err);
                        }catch(er){
                            // console.log('Error Http Request')
                            console.log(er, err.responseText)
                        }
                    }
                })

            })

            $(document).ready(function() {
                var url = '<?=base_url()?>v1/auth/checktokenadmin';

                $.ajax({
                    url: url,
                    data: {
                        token: token
                    },
                    method: 'post',
                    dataType: 'json',
                    success: (res) => {
                        // console.log(res);
                    },
                    error: (err) => {
                        window.location.href = '/admin';
                    }
                })

                getSelectedProduct();

        
            } );

            function getSelectedProduct(){
                var urlData = window.location.href.split('/');
                var urlID = urlData[urlData.length -1];

                var url = '<?=base_url()?>v1/product/view/';

                $.ajax({
                    url: url,
                    data: {
                        token: token,
                        id: urlID
                    },
                    method: 'post',
                    dataType: 'json',
                    success: (res) => {
                        if(res.type == 'success'){
                            // console.log(res.response)

                            $('#name').val(res.response.name);
                            $('#price').val(res.response.price);
                            $('#image-preview').attr('src', res.response.photo);
                            $('#description').val(res.response.description);
                        }
                    },
                    error: (err) => {
                    }
                })
            }
    </script>
</body>
</html>