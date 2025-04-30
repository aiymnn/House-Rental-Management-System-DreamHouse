<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Default Title')</title>
    <link rel="icon" href="{{ asset('uploads/ads/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        ::after,
        ::before {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }



        a {
            text-decoration: none;
        }

        li {
            list-style: none;
        }

        h1 {
            font-weight: 600;
            font-size: 1.5rem;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .wrapper {
            display: flex;
        }

        .nav-side {
            box-sizing: border-box; /* Includes padding and border in the height calculation */
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }


        .main {
            margin-left: 70px; /* Adjust this width to match the sidebar width */
            min-height: 100vh;
            width: calc(100% - 70px); /* Adjust the width based on the sidebar */
            transition: all 0.35s ease-in-out;
            background-color: #fafbfe;
        }


        #sidebar {
            width: 70px;
            min-width: 70px;
            z-index: 1000;
            height: 100vh;
            transition: all .25s ease-in-out;
            background-color: #0e2238;
            display: flex;
            flex-direction: column;
        }

        #sidebar.expand {
            width: 260px;
            min-width: 260px;
        }

        .toggle-btn {
            background-color: transparent;
            cursor: pointer;
            border: 0;
            padding: 1rem 1.5rem;
        }

        .toggle-btn i {
            font-size: 1.5rem;
            color: #FFF;
        }

        .sidebar-logo {
            margin: auto 0;
        }

        .sidebar-logo a {
            color: #FFF;
            font-size: 1.15rem;
            font-weight: 600;
        }

        #sidebar:not(.expand) .sidebar-logo,
        #sidebar:not(.expand) a.sidebar-link span {
            display: none;
        }

        .sidebar-nav {
            padding: 2rem 0;
            flex: 1 1 auto;
        }

        a.sidebar-link {
            padding: .625rem 1.625rem;
            color: #FFF;
            display: block;
            font-size: 0.9rem;
            white-space: nowrap;
            border-left: 3px solid transparent;
        }

        .sidebar-link i {
            font-size: 1.1rem;
            margin-right: .75rem;
        }

        .sidebar-nav .sidebar-item a.active {
            background-color: rgba(255, 255, 255, .075);
            border-left: 3px solid #3b7ddd;
        }

        a.sidebar-link:hover {
            background-color: rgba(255, 255, 255, .075);
            border-left: 3px solid #3b7ddd;
        }

        .sidebar-item {
            position: relative;
        }

        #sidebar:not(.expand) .sidebar-item .sidebar-dropdown {
            position: absolute;
            top: 0;
            left: 70px;
            background-color: #0e2238;
            padding: 0;
            min-width: 15rem;
            display: none;
        }

        #sidebar:not(.expand) .sidebar-item:hover .has-dropdown+.sidebar-dropdown {
            display: block;
            max-height: 15em;
            width: 100%;
            opacity: 1;
        }

        #sidebar.expand .sidebar-link[data-bs-toggle="collapse"]::after {
            border: solid;
            border-width: 0 .075rem .075rem 0;
            content: "";
            display: inline-block;
            padding: 2px;
            position: absolute;
            right: 1.5rem;
            top: 1.4rem;
            transform: rotate(-135deg);
            transition: all .2s ease-out;
        }

        #sidebar.expand .sidebar-link[data-bs-toggle="collapse"].collapsed::after {
            transform: rotate(45deg);
            transition: all .2s ease-out;
        }
    </style>

</head>
<body id="body-pd">
    <div class="wrapper">
        <div class="nav-side">
            @include('layouts.staff-nav')
        </div>
        <div class="main">
            <div class="header d-flex align-items-center justify-content-end p-2 mb-1" style="background-color: #f2f2f2;">
                @if(Auth::guard('staff')->check())
                <div class="header_img" style="width: 40px; height: 40px; overflow: hidden; padding: 0;">
                    <img src="{{ asset(Auth::guard('staff')->user()->avatar) }}" alt="" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                </div>
                <div class="ms-2">
                    {{ Auth::guard('staff')->user()->name }}
                </div>
                @endif
            </div>

            <div class="p-3 mb-2">
                @yield('content')
            </div>
        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script>
        const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});
    </script>
    <script>
        $(document).ready(function() {
    // Add event listeners for hover and click
    $('#profile-link').hover(
        function() {
            $(this).addClass('active');
        },
        function() {
            $(this).removeClass('active');
        }
    ).click(function() {
        $(this).addClass('active');
    });
});
    </script>
    <script>
        // Function to trigger file input click event when "Choose Images" button is clicked
        document.getElementById('chooseImagesBtn').addEventListener('click', function() {
          document.getElementById('imageInput').click();
        });

        // Function to display selected images
        document.getElementById('imageInput').addEventListener('change', function() {
          let imageContainer = document.getElementById('imageContainer');
          imageContainer.innerHTML = ''; // Clear previous images

          // Display selected images
          Array.from(this.files).slice(0, 20).forEach(file => {
            if (file.type.startsWith('image/')) {
              let reader = new FileReader();
              reader.onload = function(e) {
                let imgContainer = document.createElement('div');
                imgContainer.classList.add('imagePreview', 'col-md-1');

                let imgElement = document.createElement('img');
                imgElement.src = e.target.result;
                imgContainer.appendChild(imgElement);

                let deleteBtn = document.createElement('button');
                deleteBtn.classList.add('deleteImage');
                deleteBtn.innerHTML = '&times;';
                deleteBtn.onclick = function() {
                  imgContainer.remove(); // Remove the image container when delete button is clicked
                };

                imgContainer.appendChild(deleteBtn);

                imageContainer.appendChild(imgContainer);
              }
              reader.readAsDataURL(file);
            }
          });
        });
      </script>

</body>

</html>
