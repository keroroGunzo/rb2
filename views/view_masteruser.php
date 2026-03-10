<?php
require '../config/init.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../views/pageheader.php';
?>
<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="table-wrapper">
            <button type="button" id="add_button" data-toggle="modal" data-target="#modal" class="btn btn-info btn-sm pull pull-right">
                <span class="glyphicon glyphicon-plus-sign"></span> Tambah Data </button>
            <br /> <br />
            <table id="datatable1" class="table display responsive data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>NAMA</th>
                        <th>EMAIL</th>
                        <th>ROLE</th>
                        <th>STORE</th>
                        <th>IS ACTIVE</th>

                        <th>CREATED AT</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div><!-- table-wrapper -->
    </div>
</div>
<!-- BASIC MODAL -->
<div id="modal" class="modal fade effect-super-scaled" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content bd-0 tx-14">

            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">
                    Master User
                </h6>
                <button type="button" class="close"
                    data-dismiss="modal"
                    onclick="resetModalForm('#modalUser')">
                    <span>&times;</span>
                </button>
            </div>

            <form method="post" id="frm" name="frm" data-models="mdl_masteruser" data-hapus="mdl_deleteuser" data-getID="mdl_getiduser" data-hapus="mdl_deleteuser" action=" javascript:" class="form-horizontal form-bordered">

                <div class="modal-body pd-25">
                    <div class="form-layout">

                        <input type="hidden" name="id" id="user_id" data-json = "id">

                        <div class="row">

                            <!-- NAMA -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Nama <span class="tx-danger">*</span></label>
                                    <input type="text"
                                        class="form-control"
                                        name="name"
                                        required data-json="name">
                                </div>
                            </div>

                            <!-- EMAIL -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Email <span class="tx-danger">*</span></label>
                                    <input type="email"
                                        class="form-control"
                                        name="email"
                                        required data-json="email">
                                </div>
                            </div>

                            <!-- PASSWORD -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Password
                                        <small class="text-muted">(Kosongkan jika tidak diubah)</small>
                                    </label>
                                    <input type="password"
                                        class="form-control"
                                        name="password">
                                </div>
                            </div>

                            <!-- ROLE -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Role <span class="tx-danger">*</span></label>
                                    <select name="role"
                                        class="form-control select2"
                                        required data-json="role">
                                        <option value="">-- Pilih Role --</option>
                                        <option value="admin">Admin</option>
                                        <option value="cashier">Cashier</option>
                                    </select>
                                </div>
                            </div>

                            <!-- STORE -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Store</label>
                                    <select name="store_id" id="store_id"
                                        class="form-control select2"
                                        data-json="store_id">
                                        <option value="">-- Semua Store (Admin) --</option>
                                        <!-- load via AJAX -->
                                    </select>
                                </div>
                            </div>

                            <!-- STATUS -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="is_active"
                                        class="form-control select2"
                                        data-json="is_active">
                                        <option value="1">Aktif</option>
                                        <option value="0">Non Aktif</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-info"
                        onclick="insertFormdata('frm')">
                        Simpan
                    </button>
                    <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                        onclick="resetModalForm('#modal')">
                        Batal
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>