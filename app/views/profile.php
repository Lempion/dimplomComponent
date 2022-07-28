<?php $this->layout('template', ['title' => 'Профиль']);?>
<main id="js-page-content" role="main" class="page-content mt-3">
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fa-solid fa-user'></i> <?php echo $user['username']; ?>
        </h1>
    </div>
    <div class="row">
        <div class="col-lg-6 col-xl-6 m-auto">
            <!-- profile summary -->
            <div class="card mb-g rounded-top">
                <div class="row no-gutters row-grid">
                    <div class="col-12">
                        <div class="d-flex flex-column align-items-center justify-content-center p-4">
                            <img src="/img/demo/avatars/avatar-admin-lg.png"
                                 class="rounded-circle shadow-2 img-thumbnail" alt="">
                            <h5 class="mb-0 fw-700 text-center mt-3">
                                <?php echo $user['username']; ?>
                            </h5>
                            <div class="mt-4 text-center demo">
                                <a href="https://instaram.com/<?php echo $user['inst']; ?>" class="fs-xl"
                                   style="color:#C13584">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="https://vk.com/<?php echo $user['vk']; ?>" class="fs-xl" style="color:#4680C2">
                                    <i class="fab fa-vk"></i>
                                </a>
                                <a href="https://tg.com/<?php echo $user['tg']; ?>;" class="fs-xl"
                                   style="color:#0088cc">
                                    <i class="fab fa-telegram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 text-center">
                            <a href="tel:<?php echo $user['phone'];?>" class="mt-1 d-block fs-sm fw-400 text-dark">
                                <i class="fas fa-mobile-alt text-muted mr-2"></i> <?php echo $user['phone'];?></a>
                            <a href="mailto:<?php echo $user['email'];?>" class="mt-1 d-block fs-sm fw-400 text-dark">
                                <i class="fas fa-mouse-pointer text-muted mr-2"></i> <?php echo $user['email'];?></a>
                            <address class="fs-sm fw-400 mt-4 text-muted">
                                <i class="fas fa-map-pin mr-2"></i> <?php echo $user['address'];?>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>

<script src="/js/vendors.bundle.js"></script>
<script src="/js/app.bundle.js"></script>
<script>

    $(document).ready(function () {

    });

</script>