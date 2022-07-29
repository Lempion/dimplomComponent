<?php $this->layout('template', ['title' => 'Изменить данные']);?>
    <main id="js-page-content" role="main" class="page-content mt-3">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class="fa fa-edit" aria-hidden="true"></i> Редактировать
            </h1>

        </div>
        <form action="/update_data/<?php echo $user['id'];?>" method="post">
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
                                    <input type="text" name="username" id="simpleinput" class="form-control" value="<?php echo $user['username'];?>">
                                </div>

                                <!-- title -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Место работы</label>
                                    <input type="text" name="position" id="simpleinput" class="form-control" value="<?php echo $user['position'];?>">
                                </div>

                                <!-- tel -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Номер телефона</label>
                                    <input type="text" name="phone" id="simpleinput" class="form-control" value="<?php echo $user['phone'];?>">
                                </div>

                                <!-- address -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Адрес</label>
                                    <input type="text" name="address" id="simpleinput" class="form-control" value="<?php echo $user['address'];?>">
                                </div>
                                <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                    <button class="btn btn-warning">Редактировать</button>
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
    <script>

        $(document).ready(function()
        {

            $('input[type=radio][name=contactview]').change(function()
                {
                    if (this.value == 'grid')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-g');
                        $('#js-contacts .col-xl-12').removeClassPrefix('col-xl-').addClass('col-xl-4');
                        $('#js-contacts .js-expand-btn').addClass('d-none');
                        $('#js-contacts .card-body + .card-body').addClass('show');

                    }
                    else if (this.value == 'table')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-1');
                        $('#js-contacts .col-xl-4').removeClassPrefix('col-xl-').addClass('col-xl-12');
                        $('#js-contacts .js-expand-btn').removeClass('d-none');
                        $('#js-contacts .card-body + .card-body').removeClass('show');
                    }

                });

                //initialize filter
                initApp.listFilter($('#js-contacts'), $('#js-filter-contacts'));
        });

    </script>
</body>