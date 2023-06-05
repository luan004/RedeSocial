<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand">Navbar</a>
            <div class="d-flex">
                <form class="me-2" role="search">
                    <input class="form-control me-2" type="search" placeholder="Pesquisar..." aria-label="Search">
                </form>
                <button class="btn btn-outline-success me-2">
                    <i class="fa fa-user"></i>
                </button>
                <button id="switchTheme" class="btn btn-outline-secondary">
                    <i id="switchThemeIcon" class="fa fa-sun"></i>
                </button>
            </div>
        </div>
    </nav>

    <div class="row m-4 justify-content-center">
        <!-- NAVEGAÇÃO HORIZONTAL -->
        <div class="col-0 col-md-3">
            <div class="card p-4">
                <nav class="nav nav-pills flex-column">
                    <a class="my-2 nav-link active" href="#">
                        <i class="fa fa-feed"></i>
                        Feed
                    </a>
                    <a class="my-2 nav-link" href="#">
                        <i class="fa fa-user"></i>
                        Meu Perfil
                    </a>
                    <a class="my-2 nav-link" href="#">
                        <i class="fa fa-gear"></i>
                        Configurações
                    </a>
                </nav>
            </div>
        </div>

        <!-- FEED -->
        <div class="col-12 col-md-4">
            
            <div class="card mb-4">
                <div class="card-header">Titulo</div>
                <img src="..." class="card-img-top" alt="...">
                <div class="card-footer">
                    <p class="card-text">Texto Texto Texto</p>
                    <p class="card-text d-flex">
                        <button class="btn btn-sm btn-outline-danger me-1">C</button>
                        <button class="btn btn-sm btn-outline-success">C</button>
                        <small class="text-body-secondary ms-auto">05/06/2023 - 15:50</small>
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Titulo</div>
                <img src="..." class="card-img-top" alt="...">
                <div class="card-footer">
                    <p class="card-text">Texto Texto Texto</p>
                    <p class="card-text"><small class="text-body-secondary">05/06/2023 - 15:50</small></p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Titulo</div>
                <img src="..." class="card-img-top" alt="...">
                <div class="card-footer">
                    <p class="card-text">Texto Texto Texto</p>
                    <p class="card-text"><small class="text-body-secondary">05/06/2023 - 15:50</small></p>
                </div>
            </div>

        </div>

        <!-- SIDEBAR -->
        <div class="col-12 col-md-3">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h6>S</h6>   
                </div>
                <div class="card-body">
                    
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h6>#Hastags</h6>   
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">#Teste</li>
                    <li class="list-group-item">#Teste</li>
                    <li class="list-group-item">#Teste</li>
                </ul>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h6>Amigos (5 Online)</h6>
                    <a href="" class="link-underline link-underline-opacity-0">Ver todos...</a>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-2">
                        
                        <div class="d-inline-block position-relative">
                            <img src="https://placehold.co/40x40" class="rounded" alt="">
                            <span class="position-absolute top-10 start-100 translate-middle p-1 bg-success border border-light rounded-circle"></span>
                        </div>
                        Nome
                    </li>
                    <li class="list-group-item px-2">
                        <div class="d-inline-block position-relative">
                            <img src="https://placehold.co/40x40" class="rounded" alt="">
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle"></span>
                        </div>
                        Nome
                    </li>
                    <li class="list-group-item px-2">
                        <div class="d-inline-block position-relative">
                            <img src="https://placehold.co/40x40" class="rounded" alt="">
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                        </div>
                        Nome
                    </li>
                </ul>
            </div>
        </div> 
    </div>


</body>
<script src="nav.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</html>