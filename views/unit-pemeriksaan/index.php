<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $pertanyaanAnmesis app\models\anamnesa\PertanyaanAnmnesis */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Unit Pemeriksaan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($pertanyaanAnmesis, 'nomor_rekam_medik')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Unit Pemeriksaan Dokter Umum</h4>
                    <p class="card-title-desc">Unit Pemeriksaan DOkter Umum Untuk MCU</p>

                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-justified" role="tablist">
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block">Data Pelayanan</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#profile-1" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block">Anamnesis</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#messages-1" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                <span class="d-none d-sm-block">Pemeriksaan Fisik</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-toggle="tab" href="#settings-1" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block">Settings</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane active" id="home-1" role="tabpanel">
                            <div class="card card-body">
                                <h4 class="card-title">Anamnesis</h4>
                                <p class="card-title-desc">Anamnesis yang baik harus mengacu pada pertanyaan yang sistematis, yaitu dengan berpedoman pada empat pokok pikiran (The Fundamental Four)dan tujuh butir mutiara anamnesis (The Sacred Seven)</p>


                                <div class="pertanyaan-anmnesis-form">
                                    <?= $form->field($pertanyaanAnmesis, 'jawaban1')
                                        ->textarea(['rows' => 6,])
                                        ->label("Dilakukan Secara Allananmnesi / Autoananmnesis Dengan") ?>

                                    <?= $form->field($pertanyaanAnmesis, 'jawaban2')
                                        ->textarea(['rows' => 6,])
                                        ->label('A. Alasan Kedatangan / Keluhan Utama (Termasuk keluhan yang masih dirasakan pada kunjungan ulangan, harapan kekhawatiran,presepsi pasien mengenai keluhan /Penyakit)') ?>

                                    <?= $form->field($pertanyaanAnmesis, 'jawaban3')->textarea(['rows' => 6])->label('B. Keluhan Lain / tambahan ') ?>

                                    <?= $form->field($pertanyaanAnmesis, 'jawaban4')->textarea(['rows' => 6])->label('C. Riwayat Perjalanan Penyakit Sekarang : harus ditulis secara kronologis!!! (uraikan sejak timbul hingga berkembangnya penyakit, obatan-obatan yang telah diminum, pelayanan kesahatan yang telah diperolah termasuk sikap dan perilaku pasien, keluarga, lingkungan terhadap masalah yang ada) ') ?>

                                    <?= $form->field($pertanyaanAnmesis, 'jawaban5')->textarea(['rows' => 6])->label('D. Riwayat Penyakit Keluarga : (uraian penyakit yang ada pada keluarga baik yang sama, berbeda, maupun yang tidak berhubungan dengan masalahn yang ada saat ini, termasuk bagaiman cara anggota keluarga tersebut menghadapinya) ') ?>

                                    <?= $form->field($pertanyaanAnmesis, 'jawaban6')->textarea(['rows' => 6])->label('E. Riwayat penyakit dahulu: (baik yang sama maupun yang berbeda dengan penyakit sekarang, riwayat pengobatan dan pelayanan kesehatan yang pernah diperoleh termasuk pencegahan spesifik yang pernah diterima) ') ?>


                                    <div class="row">

                                        <div class="col-lg-3">
                                            <label for="">G</label>
                                            <?= $form->field($pertanyaanAnmesis, 'g')->textInput()->label(false) ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">P</label>
                                            <?= $form->field($pertanyaanAnmesis, 'p')->textInput()->label(false) ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">A</label>
                                            <?= $form->field($pertanyaanAnmesis, 'a')->textInput()->label(false) ?>
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="">H</label>
                                            <?= $form->field($pertanyaanAnmesis, 'h')->textInput()->label(false) ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="profile-1" role="tabpanel">
                            <p class="mb-0">
                                Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna 8-bit.
                            </p>
                        </div>
                        <div class="tab-pane" id="messages-1" role="tabpanel">
                            <p class="mb-0">
                                Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free.
                            </p>
                        </div>
                        <div class="tab-pane" id="settings-1" role="tabpanel">
                            <p class="mb-0">
                                Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>