<!DOCTYPE html>
<html lang="en">
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
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Pesquisar..." aria-label="Search">
                </form>
                <button class="btn btn-outline-success">
                    <i class="fa fa-user"></i>
                </button>
            </div>
        </div>
    </nav>

    <div class="row m-4 justify-content-center">
        <!-- NAVEGAÇÃO HORIZONTAL -->
        <div class="col-3">
            <div class="container card"></div>
        </div>

        <!-- FEED -->
        <div class="col-4">
            
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
        <div class="col-3">
            <div class="card container">
                
            </div>
        </div> 
    </div>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</html>