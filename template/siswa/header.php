<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    
    <title>Tk Cahaya Nusantara</title>
    <style>
        .jumbotron {
            position: relative;
            background: url('assets/img/home.jpeg') no-repeat center center;
            background-size: cover;
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            z-index: 1;
        }

        .jumbotron::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Adjust the opacity as needed */
            z-index: -1;
        }

        .jumbotron h1 {
            font-size: 4em;
        }

        .jumbotron p {
            font-size: 1.5em;
        }

        .sejarah {
            height: 400px;
            /* background-color: black; */
            padding-top: 100px;
        }
        .swiper-container {
            width: 100%;
            height: 100vh;
        }
        .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light border">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="assets/img/logo.png" height="30" alt="tkcahayanusantara" /></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-dark active" aria-current="page" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="pendaftaran.php">PPDB</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="login.php">login</a></li>
                </ul>
            </div>
        </div>
    </nav>