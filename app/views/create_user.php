<?php $this->layout('template', ['title' => 'Создание пользователя']);
d($faker) ?>
<main id="js-page-content" role="main" class="page-content mt-3">
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-plus-circle'></i> Добавить пользователя
        </h1>


    </div>
    <form action="/add_user" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-container">
                        <div class="panel-hdr">
                            <h2>Общая информация</h2>
                        </div>
                        <div class="panel-content">
                            <!-- username -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Имя</label>
                                <input type="text" name="username" id="simpleinput" class="form-control"
                                       value="<?php echo $faker->name; ?>">
                            </div>

                            <!-- title -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Место работы</label>
                                <input type="text" name="position" id="simpleinput" class="form-control" value="<?php echo $faker->catchPhrase; ?>">
                            </div>

                            <!-- tel -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Номер телефона</label>
                                <input type="text" name="phone" id="simpleinput" class="form-control"
                                       value="<?php echo $faker->e164PhoneNumber; ?>">
                            </div>

                            <!-- address -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Адрес</label>
                                <input type="text" name="address" id="simpleinput" class="form-control"
                                       value="<?php echo $faker->address; ?>">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-container">
                        <div class="panel-hdr">
                            <h2>Безопасность и Медиа</h2>
                        </div>
                        <div class="panel-content">
                            <!-- email -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Email</label>
                                <input type="text" name="email" id="simpleinput" class="form-control" value="<?php echo $faker->email; ?>">
                            </div>

                            <!-- password -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Пароль</label>
                                <input type="password" name="password" id="simpleinput" class="form-control">
                            </div>


                            <!-- status -->
                            <div class="form-group">
                                <label class="form-label" for="example-select">Выберите статус</label>
                                <select class="form-control" name="state" id="example-select">
                                    <option value="success">Онлайн</option>
                                    <option value="warning">Отошел</option>
                                    <option value="danger">Не беспокоить</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="example-fileinput">Загрузить аватар</label>
                                <input type="file" id="example-fileinput" class="form-control-file" name="avatar" accept="image/*"/>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-container">
                        <div class="panel-hdr">
                            <h2>Социальные сети</h2>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- vk -->
                                    <div class="input-group input-group-lg bg-white shadow-inset-2 mb-2">
                                        <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0 py-1 px-3">
                                                    <span class="icon-stack fs-xxl">
                                                        <i class="base-7 icon-stack-3x" style="color:#4680C2"></i>
                                                        <i class="fab fa-vk icon-stack-1x text-white"></i>
                                                    </span>
                                                </span>
                                        </div>
                                        <input type="text" name="vk"
                                               class="form-control border-left-0 bg-transparent pl-0" value="<?php echo $faker->userName; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- telegram -->
                                    <div class="input-group input-group-lg bg-white shadow-inset-2 mb-2">
                                        <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0 py-1 px-3">
                                                    <span class="icon-stack fs-xxl">
                                                        <i class="base-7 icon-stack-3x" style="color:#38A1F3"></i>
                                                        <i class="fab fa-telegram icon-stack-1x text-white"></i>
                                                    </span>
                                                </span>
                                        </div>
                                        <input type="text" name="tg"
                                               class="form-control border-left-0 bg-transparent pl-0" value="<?php echo $faker->userName; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- instagram -->
                                    <div class="input-group input-group-lg bg-white shadow-inset-2 mb-2">
                                        <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0 py-1 px-3">
                                                    <span class="icon-stack fs-xxl">
                                                        <i class="base-7 icon-stack-3x" style="color:#E1306C"></i>
                                                        <i class="fab fa-instagram icon-stack-1x text-white"></i>
                                                    </span>
                                                </span>
                                        </div>
                                        <input type="text" name="inst"
                                               class="form-control border-left-0 bg-transparent pl-0" value="<?php echo $faker->userName; ?>">
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                    <button class="btn btn-success" type="submit">Добавить</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</main>

<script src="/js/vendors.bundle.js"></script>
<script src="/js/app.bundle.js"></script>
</body>