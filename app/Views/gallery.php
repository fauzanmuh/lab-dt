<section class="bg-white" style="min-height: 100vh;">
    <div class="container py-5">
        <h2 class="text-vision-gradient" style="font-size: 40px; font-weight: bold;">Galeri Foto Laboratorium
        </h2>

        <ul class="nav nav-tabs mt-5 gallery-tabs justify-content-start">
            <li class="nav-item">
                <a class="nav-link active" href="#">Semua</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Kegiatan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Ruang Lab</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Acara</a>
            </li>
        </ul>

        <div class="row justify-content-center align-items-center g-4 mt-2">
            <div class="col-6 col-lg-4">
                <img src="/assets/images/dummy-image.png" alt="Gallery Image"
                    class="img-fluid rounded-3 border gallery-img" style="width: 344px; height: auto; cursor: pointer;"
                    data-bs-toggle="modal" data-bs-target="#imageModal" data-image="/assets/images/dummy-image.png">
            </div>
            <div class="col-6 col-lg-4">
                <img src="/assets/images/dummy-image.png" alt="Gallery Image"
                    class="img-fluid rounded-3 border gallery-img" style="width: 344px; height: auto; cursor: pointer;"
                    data-bs-toggle="modal" data-bs-target="#imageModal" data-image="/assets/images/dummy-image.png">
            </div>
            <div class="col-6 col-lg-4">
                <img src="/assets/images/dummy-image.png" alt="Gallery Image"
                    class="img-fluid rounded-3 border gallery-img" style="width: 344px; height: auto; cursor: pointer;"
                    data-bs-toggle="modal" data-bs-target="#imageModal" data-image="/assets/images/dummy-image.png">
            </div>
            <div class="col-6 col-lg-4">
                <img src="/assets/images/dummy-image.png" alt="Gallery Image"
                    class="img-fluid rounded-3 border gallery-img" style="width: 344px; height: auto; cursor: pointer;"
                    data-bs-toggle="modal" data-bs-target="#imageModal" data-image="/assets/images/dummy-image.png">
            </div>
            <div class="col-6 col-lg-4">
                <img src="/assets/images/dummy-image.png" alt="Gallery Image"
                    class="img-fluid rounded-3 border gallery-img" style="width: 344px; height: auto; cursor: pointer;"
                    data-bs-toggle="modal" data-bs-target="#imageModal" data-image="/assets/images/dummy-image.png">
            </div>
            <div class="col-6 col-lg-4">
                <img src="/assets/images/dummy-image.png" alt="Gallery Image"
                    class="img-fluid rounded-3 border gallery-img" style="width: 344px; height: auto; cursor: pointer;"
                    data-bs-toggle="modal" data-bs-target="#imageModal" data-image="/assets/images/dummy-image.png">
            </div>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center align-items-center mx-auto mt-4 p-2 rounded-3 custom-pagination"
                style="width: fit-content; background-color: #F0F0F0;">
                <li class="page-item">
                    <a class="page-link arrow" href="#"><i class="bi bi-chevron-left"></i></a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">....</a></li>
                <li class="page-item"><a class="page-link" href="#">10</a></li>
                <li class="page-item">
                    <a class="page-link arrow" href="#"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>

    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 bg-transparent">
            <img id="modalImage" src="" class="img-fluid rounded-3">
        </div>
    </div>
</div>